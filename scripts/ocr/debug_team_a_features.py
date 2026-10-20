import cv2
import numpy as np

def clean_jersey_cell(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 18)).astype(np.uint8) * 255
    thresh[:2, :] = 0; thresh[-2:, :] = 0
    thresh[:, :2] = 0; thresh[:, -2:] = 0
    
    # Remove horizontal underline lines
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Close small gaps
    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
    
    # Remove small noise
    num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        if stats[i, cv2.CC_STAT_AREA] >= 10:
            clean[labels == i] = 255
            
    pts = cv2.findNonZero(clean)
    if pts is None: return None, (0,0,0,0)
    x, y, w, h = cv2.boundingRect(pts)
    return clean[y:y+h, x:x+w], (x, y, w, h)

a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}

for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    crop, (x, y, w, h) = clean_jersey_cell(img)
    if crop is not None:
        # Check digit 1 and digit 2
        mid = w // 2
        l_ink = np.count_nonzero(crop[:, :mid])
        r_ink = np.count_nonzero(crop[:, mid:])
        l_top = np.count_nonzero(crop[:h//2, :mid])
        l_bot = np.count_nonzero(crop[h//2:, :mid])
        r_top = np.count_nonzero(crop[:h//3, mid:])
        r_mid = np.count_nonzero(crop[h//3:2*h//3, mid:])
        
        # Check holes
        cnts, hier = cv2.findContours(crop.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
        holes = sum(1 for row in hier[0] if row[3] != -1) if hier is not None else 0
        
        print(f"Row {r:2d} (exp {exp:>2s}): w={w:2d}, h={h:2d}, asp={w/h:.2f}, holes={holes}, l/r={l_ink/(r_ink or 1):.2f}, l_top/bot={l_top/(l_bot or 1):.2f}, r_top/mid={r_top/(r_mid or 1):.2f}")
