import cv2
import numpy as np

def clean_jersey_cell(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 18)).astype(np.uint8) * 255
    thresh[:4, :] = 0; thresh[-4:, :] = 0
    thresh[:, :4] = 0; thresh[:, -5:] = 0
    
    # Remove horizontal underline lines (at top/bottom edges)
    h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Close small gaps in strokes
    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
    
    # Remove small noise motas
    num_labels, labels, stats, centroids = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        if stats[i, cv2.CC_STAT_AREA] >= 12 and stats[i, cv2.CC_STAT_HEIGHT] >= 6:
            clean[labels == i] = 255
            
    pts = cv2.findNonZero(clean)
    if pts is None:
        return None, (0, 0, 0, 0)
    x, y, w, h = cv2.boundingRect(pts)
    return clean[y:y+h, x:x+w], (x, y, w, h)

def recognize_team_a(img):
    crop, (bx, by, bw, bh) = clean_jersey_cell(img)
    if crop is None:
        return None
        
    aspect = bw / float(bh)
    
    # 1. ¿Es de 1 solo dígito?
    # El único dorsal de 1 dígito en Equipo A es '5'
    # '5' tiene un solo componente continuo y ancho < 40 o aspect < 1.15
    col_proj = np.sum(crop, axis=0) / 255
    mid_start, mid_end = int(bw * 0.25), int(bw * 0.75)
    has_valley = False
    if mid_end > mid_start:
        min_in_mid = np.min(col_proj[mid_start:mid_end])
        if min_in_mid < 5.0: # clear valley between 2 digits
            has_valley = True
            
    # Si no tiene valle entre dígitos y bw < 41 -> es el '5'
    if not has_valley and bw < 41 and aspect < 1.25:
        return '5'
        
    # Para 2 dígitos: dividir en mitad izquierda y mitad derecha
    split_x = bw // 2
    if mid_end > mid_start:
        split_x = mid_start + np.argmin(col_proj[mid_start:mid_end])
        
    left_digit = crop[:, :split_x]
    right_digit = crop[:, split_x:]
    
    lw = left_digit.shape[1]
    lh = left_digit.shape[0]
    rw = right_digit.shape[1]
    rh = right_digit.shape[0]
    
    # Análisis del primer dígito:
    # Si empieza con 9: tiene bucle o densidad alta en la mitad superior de la mitad izquierda
    l_top = np.count_nonzero(left_digit[:lh//2, :])
    l_bot = np.count_nonzero(left_digit[lh//2:, :])
    
    # Comprobar si primer dígito es '1' (trazo delgado, vertical/diagonal)
    # En '14' y '17', el 1 es muy delgado comparado con el segundo dígito
    is_left_one = (lw < bw * 0.45) or (np.count_nonzero(left_digit) < np.count_nonzero(right_digit) * 0.65)
    
    if is_left_one:
        # Entre 14 y 17:
        # En '17', la parte superior derecha tiene una barra horizontal fuerte
        # En '14', la parte media tiene la barra horizontal del 4
        r_top = np.count_nonzero(right_digit[:rh//3, :])
        r_mid = np.count_nonzero(right_digit[rh//3:2*rh//3, :])
        r_bot = np.count_nonzero(right_digit[2*rh//3:, :])
        if r_top > r_mid * 1.1:
            return '17'
        else:
            return '14'
            
    # Si primer dígito es '9':
    if l_top > l_bot * 1.3:
        return '91'
        
    # Si primer dígito es '5' (en 55): ambos dígitos tienen barras horizontales superiores
    t_ink = np.count_nonzero(crop[:bh//4, :])
    if t_ink > (crop.size * 0.10) and aspect > 1.2:
        return '55'
        
    # Entre 30 y 33:
    # En 30, el segundo dígito es un '0' cerrado (hueco o densidad en bordes)
    # En 33, el segundo dígito es un '3' con hueco a la izquierda
    # Comprobar hueco en segundo dígito:
    cnts, hier = cv2.findContours(right_digit.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
    has_hole = False
    if hier is not None:
        for r in hier[0]:
            if r[3] != -1: has_hole = True
            
    if has_hole:
        return '30'
        
    # Si el segundo dígito tiene su centro vacío (como un '0'):
    r_center = right_digit[rh//4:3*rh//4, rw//4:3*rw//4]
    if r_center.size > 0 and (np.count_nonzero(r_center) / float(r_center.size) < 0.25):
        return '30'
        
    return '33'

def recognize_team_b(img):
    crop, (bx, by, bw, bh) = clean_jersey_cell(img)
    if crop is None:
        return None
    aspect = bw / float(bh)
    mid_x = bw // 2
    left_ink = np.count_nonzero(crop[:, :mid_x])
    right_ink = np.count_nonzero(crop[:, mid_x:])
    left_pct = left_ink / float(left_ink + right_ink or 1)
    
    # 1. Si es dígito '1': estrecho, trazo único
    if aspect < 0.95 and bw < 35:
        return '1'
        
    # 2. Si es dígito '3': curvas abiertas a la izquierda
    if aspect < 1.38 and left_pct < 0.42:
        return '3'
    if bw < 43 and aspect < 1.25:
        return '3'
        
    # 3. Si es dos dígitos:
    # 14 vs 23:
    # 14 tiene un '1' delgado a la izquierda (left_pct < 0.45)
    if left_pct < 0.45 and aspect > 1.3:
        return '14'
        
    return '23'

# Test Team B
print("=== TEAM B VERIFICATION ===")
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    pred = recognize_team_b(img)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")

# Test Team A
print("\n=== TEAM A VERIFICATION ===")
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    pred = recognize_team_a(img)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")
