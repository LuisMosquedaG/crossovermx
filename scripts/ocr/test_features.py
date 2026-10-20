import cv2
import numpy as np

def extract_digit_features(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 28)).astype(np.uint8) * 255
    # Remove outer 5px border
    thresh[:5, :] = 0
    thresh[-5:, :] = 0
    thresh[:, :5] = 0
    thresh[:, -5:] = 0
    
    # Clean small noise
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel)
    
    pts = cv2.findNonZero(thresh)
    if pts is None:
        return None
    x, y, w, h = cv2.boundingRect(pts)
    crop = thresh[y:y+h, x:x+w]
    
    # Topology: find contours and hierarchy to check holes (like in 0, 4, 6, 8, 9)
    cnts, hier = cv2.findContours(crop.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
    num_holes = 0
    if hier is not None:
        for h_row in hier[0]:
            if h_row[3] != -1: # has parent -> is hole
                num_holes += 1
                
    mid_x = w // 2
    left_ink = np.count_nonzero(crop[:, :mid_x])
    right_ink = np.count_nonzero(crop[:, mid_x:])
    
    return {
        'w': w, 'h': h, 'aspect': w/h, 
        'holes': num_holes,
        'left_ink_pct': left_ink / (left_ink + right_ink or 1),
    }

print("=== TEAM B JERSEYS ===")
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    feat = extract_digit_features(img)
    if feat:
        print(f"Row {r:2d} (exp {exp:>2s}): w={feat['w']:2d}, h={feat['h']:2d}, asp={feat['aspect']:.2f}, holes={feat['holes']}, left_ink={feat['left_ink_pct']:.2f}")

print("\n=== TEAM A JERSEYS ===")
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    feat = extract_digit_features(img)
    if feat:
        print(f"Row {r:2d} (exp {exp:>2s}): w={feat['w']:2d}, h={feat['h']:2d}, asp={feat['aspect']:.2f}, holes={feat['holes']}, left_ink={feat['left_ink_pct']:.2f}")
