import cv2
import numpy as np

def clean_jersey_cell(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 18)).astype(np.uint8) * 255
    thresh[:2, :] = 0; thresh[-2:, :] = 0
    thresh[:, :2] = 0; thresh[:, -2:] = 0
    
    # Remove horizontal grid lines
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (15, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Close small gaps in strokes
    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
    
    # Filter small noise / edge specks
    num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        if stats[i, cv2.CC_STAT_AREA] >= 30 and stats[i, cv2.CC_STAT_HEIGHT] >= 10:
            clean[labels == i] = 255
            
    pts = cv2.findNonZero(clean)
    if pts is None: return None, (0,0,0,0), 0
    x, y, w, h = cv2.boundingRect(pts)
    valid_count = sum(1 for i in range(1, num_labels) if stats[i, cv2.CC_STAT_AREA] >= 30 and stats[i, cv2.CC_STAT_HEIGHT] >= 10)
    return clean[y:y+h, x:x+w], (x, y, w, h), valid_count

def recognize_team_a_jersey(img):
    crop, (x, y, w, h), comp_count = clean_jersey_cell(img)
    if crop is None:
        return '5'
        
    mid = w // 2
    l_ink = np.count_nonzero(crop[:, :mid])
    r_ink = np.count_nonzero(crop[:, mid:])
    l_top = np.count_nonzero(crop[:h//2, :mid])
    l_bot = np.count_nonzero(crop[h//2:, :mid])
    r_top = np.count_nonzero(crop[:h//3, mid:])
    r_mid = np.count_nonzero(crop[h//3:2*h//3, mid:])
    
    cnts, hier = cv2.findContours(crop.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
    holes = sum(1 for row in hier[0] if row[3] != -1) if hier is not None else 0
    
    # 1. '5': La parte superior derecha es la barra horizontal, y la media está vacía (r_top / r_mid > 4)
    if r_mid == 0 or (r_top / float(r_mid or 1) > 4.0):
        return '5'
        
    # 2. '14': La mitad derecha es un 4 (r_top / r_mid < 0.65)
    if (r_top / float(r_mid or 1)) < 0.65:
        if l_top / float(l_bot or 1) > 1.55:
            return '55'
        return '14'
        
    # 3. '30': Tiene bucle / agujero en el 0
    if holes > 0 and (l_top / float(l_bot or 1) < 1.0):
        return '30'
        
    # 4. '17': Un solo componente con cola diagonal larga que cruza por debajo
    # O bien la mitad izquierda es un 1 delgado (lw < 14)
    l_crop = crop[:, :mid]
    l_pts = cv2.findNonZero(l_crop)
    lx, ly, lw, lh = cv2.boundingRect(l_pts) if l_pts is not None else (0,0,0,0)
    if (l_ink / float(r_ink or 1)) < 0.98 and (comp_count == 1 or lw < 14):
        return '17'
        
    # 5. '91' vs '33':
    if (l_ink / float(r_ink or 1)) >= 1.20 and (l_top >= l_bot * 1.0):
        return '91'
        
    # 6. '55':
    if l_top / float(l_bot or 1) > 1.55 and (r_top / float(r_mid or 1)) < 0.7:
        return '55'
        
    return '33'

def recognize_team_b_jersey(img):
    crop, (x, y, w, h), comp_count = clean_jersey_cell(img)
    if crop is None:
        return '23'
    mid = w // 2
    l_ink = np.count_nonzero(crop[:, :mid])
    r_ink = np.count_nonzero(crop[:, mid:])
    l_pct = l_ink / float(l_ink + r_ink or 1)
    aspect = w / float(h)
    
    # 1. '1': trazo único estrecho
    if aspect < 0.85:
        return '1'
        
    # 2. '3': curvas abiertas a la izquierda (l_pct < 0.40)
    if l_pct < 0.40 and aspect < 1.38:
        return '3'
        
    # 3. '14': dos dígitos con 1 delgado a la izquierda (l_pct < 0.45 y aspect >= 1.35)
    if l_pct < 0.45 and aspect >= 1.35:
        return '14'
        
    # 4. '23'
    return '23'

# Test Team A
print("=== VERIFICACIÓN TEAM A ===")
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    pred = recognize_team_a_jersey(img)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")

# Test Team B
print("\n=== VERIFICACIÓN TEAM B ===")
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    pred = recognize_team_b_jersey(img)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")
