import cv2
import numpy as np

roster_a = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg')

# 12 player rows: starts at y=82, each row is 65px
row_h = 65
start_y = 82

foul_cols = [
    (645, 715), # F1
    (715, 772), # F2
    (772, 835), # F3
    (835, 890), # F4
    (890, 940)  # F5
]

print("=== DETECCIÓN DE FALTAS - EQUIPO A ===")
for r in range(12):
    y1 = start_y + r * row_h
    y2 = y1 + row_h
    row_img = roster_a[y1:y2, :]
    
    # Check if there is player data in this row (check name and jersey cell)
    dorsal_cell = row_img[:, 590:645]
    gray_d = cv2.cvtColor(dorsal_cell, cv2.COLOR_BGR2GRAY)
    _, bin_d = cv2.threshold(gray_d, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    d_ink = np.count_nonzero(bin_d[5:-5, 5:-5]) / (bin_d[5:-5, 5:-5].size or 1)
    
    # If no ink in dorsal cell and name cell, skip empty rows
    if d_ink < 0.03:
        continue
        
    cv2.imwrite(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_row{r}.jpg', dorsal_cell)
    
    # Count fouls in the 5 boxes
    fouls_detected = 0
    box_inks = []
    for f_idx, (fx1, fx2) in enumerate(foul_cols):
        foul_box = row_img[6:-6, fx1+4:fx2-4]
        gray_f = cv2.cvtColor(foul_box, cv2.COLOR_BGR2GRAY)
        
        # Otsu threshold
        _, bin_f = cv2.threshold(gray_f, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
        
        # Exclude cell border lines (inner margin 3px)
        inner_f = bin_f[3:-3, 3:-3]
        ink_ratio = np.count_nonzero(inner_f) / (inner_f.size or 1)
        box_inks.append(f"{ink_ratio*100:.1f}%")
        
        # Threshold for a marked foul: > 12% ink
        if ink_ratio > 0.12:
            fouls_detected += 1
            
    print(f"Jugador Fila {r+1} (Dorsal ink {d_ink*100:.1f}%): Faltas detectadas = {fouls_detected} | Casillas: {box_inks}")
