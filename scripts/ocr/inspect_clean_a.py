import cv2
import numpy as np

def clean_jersey(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 25)).astype(np.uint8) * 255
    # Remove margins
    thresh[:5, :] = 0; thresh[-5:, :] = 0
    thresh[:, :5] = 0; thresh[:, -5:] = 0
    
    # Remove horizontal underline lines
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (20, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_kernel)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Clean noise
    k = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, k)
    
    pts = cv2.findNonZero(thresh)
    if pts is None: return None
    x, y, w, h = cv2.boundingRect(pts)
    return thresh[y:y+h, x:x+w]

a_rows = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}

for r, exp in a_rows.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    crop = clean_jersey(img)
    if crop is not None:
        h, w = crop.shape
        cv2.imwrite(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/clean_dorsal_a_{r}.jpg', crop)
        # Check vertical projection to find split between digit 1 and digit 2
        col_proj = np.sum(crop, axis=0) / 255
        # Find local minimum in middle 50% of width
        mid_start, mid_end = int(w * 0.25), int(w * 0.75)
        if mid_end > mid_start:
            split_x = mid_start + np.argmin(col_proj[mid_start:mid_end])
            min_val = col_proj[split_x]
        else:
            split_x, min_val = w // 2, 0
        print(f"Row {r:2d} (exp {exp:>2s}): w={w:2d}, h={h:2d}, asp={w/h:.2f}, split_x={split_x}, min_val={min_val}")
