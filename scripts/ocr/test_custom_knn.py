import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont

def clean_jersey_cell(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 25)).astype(np.uint8) * 255
    thresh[:5, :] = 0; thresh[-5:, :] = 0
    thresh[:, :5] = 0; thresh[:, -5:] = 0
    
    # Remove horizontal underline lines
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (20, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Clean noise
    k = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, k)
    
    pts = cv2.findNonZero(thresh)
    if pts is None:
        return np.zeros((32, 32), dtype=np.uint8)
    x, y, w, h = cv2.boundingRect(pts)
    crop = thresh[y:y+h, x:x+w]
    
    canvas = np.zeros((32, 32), dtype=np.uint8)
    scale = 26.0 / max(w, h)
    nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
    resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
    ox = (32 - nw) // 2
    oy = (32 - nh) // 2
    canvas[oy:oy+nh, ox:ox+nw] = resized
    return canvas

def render_sample(text, font_path, size=28, angle=0, thickness=0):
    img = Image.new('L', (48, 48), color=0)
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(font_path, size)
    bbox = font.getbbox(text)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    draw.text(((48 - w) // 2 - bbox[0], (48 - h) // 2 - bbox[1]), text, fill=255, font=font)
    arr = np.array(img)
    
    if angle != 0:
        M = cv2.getRotationMatrix2D((24, 24), angle, 1.0)
        arr = cv2.warpAffine(arr, M, (48, 48))
        
    if thickness > 0:
        k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (thickness+1, thickness+1))
        arr = cv2.dilate(arr, k)
        
    pts = cv2.findNonZero(arr)
    if pts is None:
        return np.zeros((32, 32), dtype=np.uint8)
    x, y, w, h = cv2.boundingRect(pts)
    crop = arr[y:y+h, x:x+w]
    
    canvas = np.zeros((32, 32), dtype=np.uint8)
    scale = 26.0 / max(w, h)
    nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
    resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
    ox = (32 - nw) // 2
    oy = (32 - nh) // 2
    canvas[oy:oy+nh, ox:ox+nw] = resized
    return canvas

def extract_features(img_32x32):
    # Downsampled 16x16 density
    small = cv2.resize(img_32x32, (16, 16), interpolation=cv2.INTER_AREA).astype(np.float32) / 255.0
    f1 = small.flatten()
    
    # 4x4 zone densities
    z4 = cv2.resize(img_32x32, (4, 4), interpolation=cv2.INTER_AREA).astype(np.float32) / 255.0
    f2 = z4.flatten() * 2.0
    
    # Projections
    hp = np.sum(small, axis=1) / 16.0
    vp = np.sum(small, axis=0) / 16.0
    
    feat = np.concatenate([f1, f2, hp, vp])
    # Normalize
    norm = np.linalg.norm(feat)
    if norm > 0:
        feat = feat / norm
    return feat

def build_knn_classifier(candidate_numbers, roster_templates=None):
    train_X = []
    train_y = []
    
    fonts = [
        "C:/Windows/Fonts/inkfree.ttf",
        "C:/Windows/Fonts/segoepr.ttf",
        "C:/Windows/Fonts/comic.ttf"
    ]
    
    for num in candidate_numbers:
        # Synthetic samples
        for f in fonts:
            for sz in [22, 26, 30]:
                for ang in [-10, -5, 0, 5, 10]:
                    for th in [0, 1]:
                        s = render_sample(num, f, size=sz, angle=ang, thickness=th)
                        feat = extract_features(s)
                        train_X.append(feat)
                        train_y.append(num)
                        
        # Referee's actual handwriting from roster
        if roster_templates and num in roster_templates:
            ref_s = clean_jersey_cell(roster_templates[num])
            ref_feat = extract_features(ref_s)
            for _ in range(10): # give heavy weight to referee's actual hand
                train_X.append(ref_feat)
                train_y.append(num)
                
    train_X = np.array(train_X, dtype=np.float32)
    return train_X, train_y

def predict(feat, train_X, train_y, k=5):
    # Cosine similarity (since feats are normalized, dot product = cosine similarity)
    sims = np.dot(train_X, feat)
    top_k_idx = np.argsort(sims)[-k:]
    top_k_labels = [train_y[i] for i in top_k_idx]
    
    # Majority vote weighted by similarity
    votes = {}
    for idx in top_k_idx:
        lbl = train_y[idx]
        score = sims[idx]
        votes[lbl] = votes.get(lbl, 0.0) + score
        
    return max(votes, key=votes.get)

# Test Team B
b_cands = ['1', '3', '5', '11', '14', '23']
b_roster_imgs = {num: cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_num_b_{num}.jpg') for num in b_cands}
train_X_b, train_y_b = build_knn_classifier(b_cands, b_roster_imgs)

b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
print("=== TEAM B DIGIT RECOGNITION ===")
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    clean = clean_jersey_cell(img)
    feat = extract_features(clean)
    pred = predict(feat, train_X_b, train_y_b, k=5)
    status = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{status}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")

# Test Team A
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
a_roster_imgs = {num: cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_num_a_{num}.jpg') for num in a_cands}
train_X_a, train_y_a = build_knn_classifier(a_cands, a_roster_imgs)

a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
print("\n=== TEAM A DIGIT RECOGNITION ===")
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    clean = clean_jersey_cell(img)
    feat = extract_features(clean)
    pred = predict(feat, train_X_a, train_y_a, k=5)
    status = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{status}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")
