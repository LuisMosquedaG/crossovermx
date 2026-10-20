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
    
    # Place on 32x32 canvas maintaining aspect ratio
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
        
    # Crop to content and place on 32x32
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

# Setup HOG descriptor (32x32, cell 8x8, block 16x16, 9 bins)
hog = cv2.HOGDescriptor(
    _winSize=(32, 32),
    _blockSize=(16, 16),
    _blockStride=(8, 8),
    _cellSize=(8, 8),
    _nbins=9
)

def build_classifier(candidate_numbers, roster_templates=None):
    train_feats = []
    train_labels = []
    
    label_map = {num: i for i, num in enumerate(candidate_numbers)}
    inv_map = {i: num for num, i in label_map.items()}
    
    fonts = [
        "C:/Windows/Fonts/inkfree.ttf",
        "C:/Windows/Fonts/segoepr.ttf",
        "C:/Windows/Fonts/comic.ttf"
    ]
    
    for num in candidate_numbers:
        lbl = label_map[num]
        
        # 1. Synthetic rendered variations
        for f in fonts:
            for sz in [24, 28, 32]:
                for ang in [-10, -5, 0, 5, 10]:
                    for th in [0, 1]:
                        s = render_sample(num, f, size=sz, angle=ang, thickness=th)
                        h_desc = hog.compute(s).flatten()
                        train_feats.append(h_desc)
                        train_labels.append(lbl)
                        
        # 2. Referee's own handwriting from roster table (if provided)
        if roster_templates and num in roster_templates:
            ref_img = roster_templates[num]
            s = clean_jersey_cell(ref_img)
            h_desc = hog.compute(s).flatten()
            # Give referee's own handwriting 5x weight
            for _ in range(5):
                train_feats.append(h_desc)
                train_labels.append(lbl)
                
    X = np.array(train_feats, dtype=np.float32)
    y = np.array(train_labels, dtype=np.int32)
    
    knn = cv2.ml.KNearest_create()
    knn.train(X, cv2.ml.ROW_SAMPLE, y)
    return knn, inv_map

# Test Team B
b_cands = ['1', '3', '5', '11', '14', '23']
b_roster_imgs = {num: cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_num_b_{num}.jpg') for num in b_cands}
knn_b, map_b = build_classifier(b_cands, b_roster_imgs)

b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
print("=== TEAM B (HOG + KNN) ===")
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    clean = clean_jersey_cell(img)
    feat = hog.compute(clean).flatten().reshape(1, -1).astype(np.float32)
    _, res, _, _ = knn_b.findNearest(feat, k=3)
    pred = map_b[int(res[0][0])]
    status = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{status}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")

# Test Team A
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
a_roster_imgs = {num: cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_num_a_{num}.jpg') for num in a_cands}
knn_a, map_a = build_classifier(a_cands, a_roster_imgs)

a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
print("\n=== TEAM A (HOG + KNN) ===")
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    clean = clean_jersey_cell(img)
    feat = hog.compute(clean).flatten().reshape(1, -1).astype(np.float32)
    _, res, _, _ = knn_a.findNearest(feat, k=3)
    pred = map_a[int(res[0][0])]
    status = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{status}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")
