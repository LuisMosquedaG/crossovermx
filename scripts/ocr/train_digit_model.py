import os
import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont

def render_digit(digit, font_path, size=28, angle=0, thickness=0, dx=0, dy=0):
    text = str(digit)
    img = Image.new('L', (40, 40), color=0)
    draw = ImageDraw.Draw(img)
    try:
        font = ImageFont.truetype(font_path, size)
    except Exception:
        return None
    bbox = font.getbbox(text)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    x_pos = (40 - w) // 2 - bbox[0] + dx
    y_pos = (40 - h) // 2 - bbox[1] + dy
    draw.text((x_pos, y_pos), text, fill=255, font=font)
    arr = np.array(img)
    
    if angle != 0:
        M = cv2.getRotationMatrix2D((20, 20), angle, 1.0)
        arr = cv2.warpAffine(arr, M, (40, 40))
        
    if thickness > 0:
        k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (thickness+1, thickness+1))
        arr = cv2.dilate(arr, k)
        
    # Resize to standard 28x28 (MNIST standard)
    pts = cv2.findNonZero(arr)
    if pts is None:
        return np.zeros((28, 28), dtype=np.float32)
    x, y, w, h = cv2.boundingRect(pts)
    crop = arr[y:y+h, x:x+w]
    
    canvas = np.zeros((28, 28), dtype=np.uint8)
    scale = 20.0 / max(w, h)
    nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
    resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
    ox = (28 - nw) // 2
    oy = (28 - nh) // 2
    canvas[oy:oy+nh, ox:ox+nw] = resized
    return canvas.astype(np.float32) / 255.0

print("Generando dataset de dígitos sintéticos con fuentes de Windows...")
fonts = [
    "C:/Windows/Fonts/inkfree.ttf",
    "C:/Windows/Fonts/segoepr.ttf",
    "C:/Windows/Fonts/comic.ttf",
    "C:/Windows/Fonts/arial.ttf",
    "C:/Windows/Fonts/calibri.ttf"
]

X_list = []
y_list = []

for d in range(10):
    for f in fonts:
        if not os.path.exists(f): continue
        for sz in [22, 26, 30]:
            for ang in [-14, -7, 0, 7, 14]:
                for th in [0, 1]:
                    for dx in [-2, 0, 2]:
                        for dy in [-2, 0, 2]:
                            s = render_digit(d, f, size=sz, angle=ang, thickness=th, dx=dx, dy=dy)
                            if s is not None:
                                X_list.append(s.flatten())
                                y_list.append(d)

X = np.array(X_list, dtype=np.float32)
y = np.array(y_list, dtype=np.int32)
print(f"Dataset generado: {X.shape[0]} muestras de 28x28 ({X.shape[1]} características).")

# Entrenar MLP de 2 capas: 784 -> 128 (ReLU) -> 10 (Softmax)
np.random.seed(42)
W1 = np.random.randn(784, 128).astype(np.float32) * np.sqrt(2.0 / 784)
b1 = np.zeros((1, 128), dtype=np.float32)
W2 = np.random.randn(128, 10).astype(np.float32) * np.sqrt(2.0 / 128)
b2 = np.zeros((1, 10), dtype=np.float32)

lr = 0.005
batch_size = 128
epochs = 20
num_samples = X.shape[0]

def softmax(z):
    exp_z = np.exp(z - np.max(z, axis=1, keepdims=True))
    return exp_z / np.sum(exp_z, axis=1, keepdims=True)

print("Entrenando red neuronal local...")
for ep in range(epochs):
    perm = np.random.permutation(num_samples)
    X_shuff = X[perm]
    y_shuff = y[perm]
    
    total_loss = 0.0
    correct = 0
    
    for i in range(0, num_samples, batch_size):
        xb = X_shuff[i:i+batch_size]
        yb = y_shuff[i:i+batch_size]
        B = xb.shape[0]
        
        # Forward
        z1 = np.dot(xb, W1) + b1
        a1 = np.maximum(0, z1) # ReLU
        z2 = np.dot(a1, W2) + b2
        probs = softmax(z2)
        
        # Cross-entropy loss
        log_probs = -np.log(np.maximum(probs[np.arange(B), yb], 1e-12))
        total_loss += np.sum(log_probs)
        preds = np.argmax(probs, axis=1)
        correct += np.sum(preds == yb)
        
        # Backward
        dz2 = probs.copy()
        dz2[np.arange(B), yb] -= 1.0
        dz2 /= B
        
        dW2 = np.dot(a1.T, dz2)
        db2 = np.sum(dz2, axis=0, keepdims=True)
        
        da1 = np.dot(dz2, W2.T)
        dz1 = da1 * (z1 > 0)
        
        dW1 = np.dot(xb.T, dz1)
        db1 = np.sum(dz1, axis=0, keepdims=True)
        
        # Update (SGD con momentum básico o simple)
        W2 -= lr * dW2
        b2 -= lr * db2
        W1 -= lr * dW1
        b1 -= lr * db1
        
    acc = correct / float(num_samples)
    if (ep + 1) % 5 == 0:
        print(f"Época {ep+1:2d}/{epochs}: Loss = {total_loss/num_samples:.4f}, Exactitud = {acc*100:.2f}%")

# Guardar pesos
model_path = "c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/digit_model.npz"
np.savez_compressed(model_path, W1=W1, b1=b1, W2=W2, b2=b2)
print(f"Modelo guardado exitosamente en: {model_path} ({os.path.getsize(model_path) / 1024:.1f} KB)")
