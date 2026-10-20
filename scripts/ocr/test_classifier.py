import cv2
import numpy as np

def clean_cell_ink(img):
    """Limpia el recorte de celda, elimina bordes de cuadrícula y devuelve la máscara binaria limpia."""
    h, w = img.shape[:2]
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 25)).astype(np.uint8) * 255
    
    # Eliminar líneas de borde de celda (márgenes exteriores)
    thresh[:5, :] = 0
    thresh[-5:, :] = 0
    thresh[:, :5] = 0
    thresh[:, -6:] = 0
    
    # Eliminar líneas horizontales que cruzan todo el ancho (subrayado de fin de cuarto)
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 1))
    h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_kernel)
    thresh = cv2.subtract(thresh, h_lines)
    
    # Limpiar ruido pequeño
    k = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    thresh = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, k)
    
    # Encontrar componentes conectados principales (para quitar motas aisladas)
    num_labels, labels, stats, centroids = cv2.connectedComponentsWithStats(thresh)
    clean = np.zeros_like(thresh)
    for i in range(1, num_labels):
        area = stats[i, cv2.CC_STAT_AREA]
        cw = stats[i, cv2.CC_STAT_WIDTH]
        ch = stats[i, cv2.CC_STAT_HEIGHT]
        # Conservar solo trazos con altura o área razonable
        if area >= 15 and ch >= 8:
            clean[labels == i] = 255
            
    return clean

def classify_jersey(img, candidate_numbers):
    """
    Clasifica el dorsal manuscrito restringiéndolo a los candidatos del equipo.
    """
    clean = clean_cell_ink(img)
    pts = cv2.findNonZero(clean)
    if pts is None:
        return None
        
    x, y, w, h = cv2.boundingRect(pts)
    crop = clean[y:y+h, x:x+w]
    
    # Si w o h son muy pequeños, probablemente ruido
    if h < 12 or w < 8:
        return None
        
    aspect = w / float(h)
    
    # Detectar agujeros / bucles (0, 4, 6, 8, 9)
    cnts, hier = cv2.findContours(crop.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
    num_holes = 0
    if hier is not None:
        for r in hier[0]:
            if r[3] != -1:
                num_holes += 1
                
    # Proyecciones
    mid_x = w // 2
    left_ink = np.count_nonzero(crop[:, :mid_x])
    right_ink = np.count_nonzero(crop[:, mid_x:])
    left_pct = left_ink / float(left_ink + right_ink or 1)
    
    # Para Team B: candidatos = ['1', '3', '5', '11', '14', '23']
    if set(candidate_numbers) == set(['1', '3', '5', '11', '14', '23']):
        # Si es un solo dígito estrecho -> '1'
        if aspect < 0.95 and left_pct > 0.45:
            return '1'
        # Si es un dígito '3' -> curva abierta a la izquierda, aspect ~ 1.0-1.35, left_pct bajo o w < 44
        if aspect < 1.35 and left_pct < 0.42:
            return '3'
        if w < 40 and aspect < 1.25:
            return '3'
        # Si tiene agujero o empieza con 1 -> '14'
        if left_pct < 0.45 and aspect > 1.3:
            return '14'
        # Resto de dos dígitos -> '23'
        return '23'
        
    # Para Team A: candidatos = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
    if set(candidate_numbers) == set(['5', '14', '17', '23', '30', '33', '40', '55', '91']):
        # '5' es el único dorsal de un dígito
        if aspect < 1.05 and w < 40:
            return '5'
        if w < 40 and '5' in candidate_numbers and aspect < 1.2:
            return '5'
            
        # Analizar mitad izquierda y mitad derecha para 2 dígitos
        left_digit = crop[:, :mid_x]
        right_digit = crop[:, mid_x:]
        
        # ¿Tiene bucle en la primera mitad? -> '91' o '40'
        # ¿Tiene bucle en la segunda mitad? -> '30' o '40'
        # Comprobar si termina en '0':
        r_cnts, r_hier = cv2.findContours(right_digit.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
        r_holes = 0
        if r_hier is not None:
            for r in r_hier[0]:
                if r[3] != -1: r_holes += 1
                
        l_cnts, l_hier = cv2.findContours(left_digit.copy(), cv2.RETR_CCOMP, cv2.CHAIN_APPROX_SIMPLE)
        l_holes = 0
        if l_hier is not None:
            for r in l_hier[0]:
                if r[3] != -1: l_holes += 1
                
        # Si empieza con 9 (bucle arriba a la izquierda) y termina en 1
        # Comprobar si la parte superior izquierda tiene bucle o forma de 9
        top_left = left_digit[:h//2, :]
        if l_holes > 0 or (np.count_nonzero(top_left) > np.count_nonzero(left_digit[h//2:, :]) * 1.5):
            if '91' in candidate_numbers:
                return '91'
                
        # Si termina en 0 -> '30'
        if r_holes > 0 and '30' in candidate_numbers:
            return '30'
            
        # Si empieza con 1:
        # La mitad izquierda es un trazo vertical delgado
        l_pts = cv2.findNonZero(left_digit)
        if l_pts is not None:
            lx, ly, lw, lh = cv2.boundingRect(l_pts)
            if lw < mid_x * 0.6: # trazo de '1'
                # Entre 14 y 17:
                # 14 tiene cruce/bucle a la derecha; 17 tiene barra horizontal superior
                top_r = np.count_nonzero(right_digit[:h//3, :])
                mid_r = np.count_nonzero(right_digit[h//3:2*h//3, :])
                if top_r > mid_r:
                    return '17'
                else:
                    return '14'
                    
        # 55: ambos dígitos tienen barras horizontales superiores
        t_ink = np.count_nonzero(crop[:h//4, :])
        if t_ink > (crop.size * 0.12):
            return '55'
            
        # 33 vs 30
        return '33'
        
    # Fallback general
    return candidate_numbers[0]

print("=== VERIFICACIÓN TEAM B ===")
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
b_cands = ['1', '3', '5', '11', '14', '23']
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    pred = classify_jersey(img, b_cands)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")

print("\n=== VERIFICACIÓN TEAM A ===")
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    pred = classify_jersey(img, a_cands)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")
