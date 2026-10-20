import cv2
import numpy as np

net = cv2.dnn.readNetFromONNX('scripts/ocr/mnist.onnx')

def clean_dorsal_cell(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 18)).astype(np.uint8) * 255
    thresh[:2, :] = 0; thresh[-2:, :] = 0
    thresh[:, :2] = 0; thresh[:, -2:] = 0
    
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (20, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (2, 2))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
    
    num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        if stats[i, cv2.CC_STAT_AREA] >= 20 and stats[i, cv2.CC_STAT_HEIGHT] >= 8:
            clean[labels == i] = 255
            
    pts = cv2.findNonZero(clean)
    if pts is None: return None
    x, y, w, h = cv2.boundingRect(pts)
    return clean[y:y+h, x:x+w]

def prep_crop(crop):
    canvas = np.zeros((28, 28), dtype=np.uint8)
    h, w = crop.shape
    scale = 20.0 / max(w, h)
    nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
    resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
    ox = (28 - nw) // 2
    oy = (28 - nh) // 2
    canvas[oy:oy+nh, ox:ox+nw] = resized
    blob = (canvas.astype(np.float32) / 255.0).reshape(1, 1, 28, 28)
    net.setInput(blob)
    out = net.forward()[0]
    e = np.exp(out - np.max(out))
    probs = e / np.sum(e)
    return probs, canvas

def classify_ensemble(cell_img, candidate_numbers, roster_templates=None):
    crop = clean_dorsal_cell(cell_img)
    if crop is None:
        return str(candidate_numbers[0])
        
    h, w = crop.shape
    aspect = w / float(h)
    
    p_full, c_full = prep_crop(crop)
    p_adj = p_full.copy()
    p_adj[1] = max(p_full[1], p_full[7])
    p_adj[5] = max(p_full[5], p_full[6])
    
    # 2 dígitos
    mid = w // 2
    p_d1, _ = prep_crop(crop[:, :mid])
    p_d2, _ = prep_crop(crop[:, mid:])
    p_d1_adj = p_d1.copy()
    p_d1_adj[1] = max(p_d1[1], p_d1[7])
    p_d1_adj[5] = max(p_d1[5], p_d1[6])
    p_d2_adj = p_d2.copy()
    p_d2_adj[1] = max(p_d2[1], p_d2[7])
    p_d2_adj[5] = max(p_d2[5], p_d2[6])
    
    scores = {}
    for cand in candidate_numbers:
        s_cand = str(cand).strip()
        
        # 1. Score MNIST
        if len(s_cand) == 1:
            d = int(s_cand)
            m_score = p_adj[d]
            if aspect > 1.30: m_score *= 0.05
        elif len(s_cand) == 2:
            d1 = int(s_cand[0])
            d2 = int(s_cand[1])
            m_score = np.sqrt(p_d1_adj[d1] * p_d2_adj[d2])
            if aspect < 0.90: m_score *= 0.05
        else:
            m_score = 0.0
            
        # 2. Score de correlación con plantilla del acta (si existe)
        t_score = 1.0
        if roster_templates and s_cand in roster_templates:
            tmpl_clean = clean_dorsal_cell(roster_templates[s_cand])
            if tmpl_clean is not None:
                _, c_tmpl = prep_crop(tmpl_clean)
                res = cv2.matchTemplate(c_full, c_tmpl, cv2.TM_CCOEFF_NORMED)
                corr = max(0.0, float(res[0][0]))
                t_score = 1.0 + corr * 2.0
                
        scores[s_cand] = m_score * t_score
        
    return max(scores, key=scores.get)

# Test Team A
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
a_roster = {num: cv2.imread(f'scripts/ocr/roster_num_a_{num}.jpg') for num in a_cands}
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}

print("=== TEAM A (MNIST + ROSTER CORRELATION) ===")
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'scripts/ocr/dorsal_a_{r}.jpg')
    pred = classify_ensemble(img, a_cands, a_roster)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")

# Test Team B
b_cands = ['1', '3', '5', '11', '14', '23']
b_roster = {num: cv2.imread(f'scripts/ocr/roster_num_b_{num}.jpg') for num in b_cands}
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}

print("\n=== TEAM B (MNIST + ROSTER CORRELATION) ===")
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'scripts/ocr/dorsal_b_{r}.jpg')
    pred = classify_ensemble(img, b_cands, b_roster)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")
