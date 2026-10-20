import cv2
import numpy as np

def prep_binary(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 18)).astype(np.uint8) * 255
    thresh[:3, :] = 0; thresh[-3:, :] = 0
    thresh[:, :3] = 0; thresh[:, -3:] = 0
    
    # Remove horizontal lines
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Close small gaps
    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
    
    # Remove tiny noise
    num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        if stats[i, cv2.CC_STAT_AREA] >= 12:
            clean[labels == i] = 255
            
    pts = cv2.findNonZero(clean)
    if pts is None:
        return np.zeros((32, 32), dtype=np.uint8)
    x, y, w, h = cv2.boundingRect(pts)
    crop = clean[y:y+h, x:x+w]
    
    # Resize onto 32x32 preserving aspect ratio
    canvas = np.zeros((32, 32), dtype=np.uint8)
    scale = 26.0 / max(w, h)
    nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
    resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
    ox = (32 - nw) // 2
    oy = (32 - nh) // 2
    canvas[oy:oy+nh, ox:ox+nw] = (resized > 100).astype(np.uint8) * 255
    return canvas

# Prepare templates from roster column
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
tmpl_dt = {}
for num in a_cands:
    t_img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_num_a_{num}.jpg')
    t_bin = prep_binary(t_img)
    # Distance transform of inverted binary
    dt = cv2.distanceTransform(255 - t_bin, cv2.DIST_L2, 3)
    tmpl_dt[num] = dt

a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}

print("=== DISTANCE TRANSFORM MATCHING TEAM A ===")
correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    q_bin = prep_binary(img)
    q_pts = (q_bin > 0)
    q_count = np.count_nonzero(q_pts)
    
    best_dist = float('inf')
    best_match = None
    for num, dt in tmpl_dt.items():
        if q_count > 0:
            dist = np.mean(dt[q_pts])
        else:
            dist = float('inf')
        if dist < best_dist:
            best_dist = dist
            best_match = num
            
    status = "OK" if best_match == exp else f"MISMATCH (got {best_match})"
    if best_match == exp: correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Best match {best_match:>2s} (dist {best_dist:.2f}) [{status}]")

print(f"Accuracy: {correct}/{len(a_expected)} ({correct/len(a_expected)*100:.1f}%)")
