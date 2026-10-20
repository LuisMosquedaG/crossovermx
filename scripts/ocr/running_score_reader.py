"""
Módulo de Lectura del Tanteo Corrido (Running Score) para Actas FIBA.
Lee las 4 columnas de 40 puntos (1 a 160) tanto para Equipo A como para Equipo B.
Detecta con precisión matemática:
1. Puntos finales de Equipo A y Equipo B (detención automática al finalizar el partido).
2. Cada canasta individual (1, 2 o 3 puntos).
3. El dorsal del anotador de cada canasta mediante el reconocedor.
"""

import cv2
import numpy as np
from digit_recognizer import DigitRecognizer

COLUMN_SETS = [
    # Col 1: 1 a 40
    {'dorsal_a': (3, 65), 'pt_a': (65, 135), 'pt_b': (135, 201), 'dorsal_b': (201, 271)},
    # Col 2: 41 a 80
    {'dorsal_a': (271, 335), 'pt_a': (335, 404), 'pt_b': (404, 474), 'dorsal_b': (474, 543)},
    # Col 3: 81 a 120
    {'dorsal_a': (543, 610), 'pt_a': (610, 680), 'pt_b': (680, 749), 'dorsal_b': (749, 821)},
    # Col 4: 121 a 160
    {'dorsal_a': (821, 893), 'pt_a': (893, 960), 'pt_b': (960, 1033), 'dorsal_b': (1033, 1106)},
]

HEADER_OFFSET_Y = 63.0
ROW_HEIGHT = 51.2

def has_written_jersey(dorsal_cell, recognizer):
    """
    Comprueba si una celda de dorsal contiene un número de camiseta válido escrito a mano.
    Filtra celdas vacías, trazos de cierre verticales finos (w < 18) y subrayados de cierre (area < 160).
    """
    if dorsal_cell is None or dorsal_cell.size == 0:
        return False
    clean = recognizer.clean_jersey_cell(dorsal_cell)
    if clean is None:
        return False
    h, w = clean.shape
    area = np.count_nonzero(clean)
    # Un dorsal manuscrito legítimo tiene altura >= 22, anchura >= 18 y masa de trazo >= 160 px
    return (h >= 22 and w >= 18 and area >= 160)

def is_point_cell_marked(pt_cell):
    """
    Determina si la celda de punto fue marcada por el anotador (bolígrafo rojo o azul).
    Valida que la marca tenga forma de barra diagonal '/' o círculo 'O' (no línea de cierre).
    """
    if pt_cell is None or pt_cell.size == 0:
        return False, 0
    b, g, r = cv2.split(pt_cell)
    # Bolígrafo rojo (cuartos 1 y 3) o azul (cuartos 2 y 4)
    red_mask = (r.astype(int) - b.astype(int) > 28) & (r > 85)
    blue_mask = (b.astype(int) - r.astype(int) > 20) & (b > 85)
    pen_mask = (red_mask | blue_mask).astype(np.uint8) * 255
    pen_pixels = np.count_nonzero(pen_mask)

    if pen_pixels < 40:
        return False, pen_pixels

    # Comprobar orientación geométrica de la marca de bolígrafo
    pts = cv2.findNonZero(pen_mask)
    if pts is not None and len(pts) >= 25:
        [vx, vy, x, y] = cv2.fitLine(pts, cv2.DIST_L2, 0, 0.01, 0.01)
        angle = np.degrees(np.arctan2(vy[0], vx[0]))
        xb, yb, wb, hb = cv2.boundingRect(pts)
        asp = wb / float(hb or 1)
        # Slashes diagonales '/', círculos 'O' o subrayados de cuarto sobre el punto marcado
        if angle < -10.0 or (wb >= 16 and hb >= 14 and 0.5 <= asp <= 2.2):
            return True, pen_pixels

    return False, pen_pixels

def read_running_score(running_score_img, candidate_jerseys_a, candidate_jerseys_b):
    """
    Lee todo el tanteo corrido y devuelve:
    - score_a, score_b (totales finales exactos)
    - baskets_a, baskets_b (desglose canasta por canasta)
    - points_a, points_b (totales por jugador)
    """
    recognizer = DigitRecognizer()

    h_img, w_img = running_score_img.shape[:2]
    scale_x = w_img / 1115.0
    scale_y = h_img / 2141.0

    header_y = HEADER_OFFSET_Y * scale_y
    row_h = ROW_HEIGHT * scale_y

    score_a = 0
    score_b = 0
    baskets_a = []
    baskets_b = []
    points_a = {str(j): 0 for j in candidate_jerseys_a}
    points_b = {str(j): 0 for j in candidate_jerseys_b}

    stopped_a = False
    stopped_b = False

    # Bucle por columnas de 40 puntos
    for col_idx, col_def in enumerate(COLUMN_SETS):
        base_point = col_idx * 40
        empty_rows_streak = 0

        for r in range(1, 41):
            current_point = base_point + r
            y1 = int(header_y + (r - 1) * row_h)
            y2 = int(header_y + r * row_h)

            if y2 > h_img:
                break

            # Celdas Equipo A
            ax1 = int(col_def['pt_a'][0] * scale_x)
            ax2 = int(col_def['pt_a'][1] * scale_x)
            pt_a_cell = running_score_img[y1+5:y2-5, ax1+5:ax2-5]

            adx1 = int(col_def['dorsal_a'][0] * scale_x)
            adx2 = int(col_def['dorsal_a'][1] * scale_x)
            dorsal_a_cell = running_score_img[y1+4:y2-4, adx1+4:adx2-4]

            # Celdas Equipo B
            bx1 = int(col_def['pt_b'][0] * scale_x)
            bx2 = int(col_def['pt_b'][1] * scale_x)
            pt_b_cell = running_score_img[y1+5:y2-5, bx1+5:bx2-5]

            bdx1 = int(col_def['dorsal_b'][0] * scale_x)
            bdx2 = int(col_def['dorsal_b'][1] * scale_x)
            dorsal_b_cell = running_score_img[y1+4:y2-4, bdx1+4:bdx2-4]

            pt_a_marked, pen_a = is_point_cell_marked(pt_a_cell)
            has_dorsal_a = has_written_jersey(dorsal_a_cell, recognizer)

            pt_b_marked, pen_b = is_point_cell_marked(pt_b_cell)
            has_dorsal_b = has_written_jersey(dorsal_b_cell, recognizer)

            basket_found_this_row = False

            # Validar canasta Equipo A:
            if not stopped_a:
                pts_scored_a = current_point - score_a
                if 1 <= pts_scored_a <= 3:
                    if pt_a_marked and has_dorsal_a:
                        score_a = current_point
                        pred_jersey = recognizer.classify_jersey(dorsal_a_cell, candidate_jerseys_a)
                        baskets_a.append({
                            'cumulative_score': score_a,
                            'points': pts_scored_a,
                            'jersey': pred_jersey
                        })
                        points_a[pred_jersey] = points_a.get(pred_jersey, 0) + pts_scored_a
                        basket_found_this_row = True
                elif pts_scored_a > 3 and score_a > 0:
                    stopped_a = True

            # Validar canasta Equipo B:
            if not stopped_b:
                pts_scored_b = current_point - score_b
                if 1 <= pts_scored_b <= 3:
                    if pt_b_marked and has_dorsal_b:
                        score_b = current_point
                        pred_jersey = recognizer.classify_jersey(dorsal_b_cell, candidate_jerseys_b)
                        baskets_b.append({
                            'cumulative_score': score_b,
                            'points': pts_scored_b,
                            'jersey': pred_jersey
                        })
                        points_b[pred_jersey] = points_b.get(pred_jersey, 0) + pts_scored_b
                        basket_found_this_row = True
                elif pts_scored_b > 3 and score_b > 0:
                    stopped_b = True

            # Control de fin de partido global
            if basket_found_this_row:
                empty_rows_streak = 0
            else:
                if (score_a > 0 or score_b > 0):
                    empty_rows_streak += 1
                    if empty_rows_streak >= 4 and r > max(score_a, score_b):
                        stopped_a = True
                        stopped_b = True
                        break

        if stopped_a and stopped_b:
            break

    return {
        'score_a': score_a,
        'score_b': score_b,
        'baskets_a': baskets_a,
        'baskets_b': baskets_b,
        'points_a': points_a,
        'points_b': points_b
    }

if __name__ == "__main__":
    import sys
    sys.path.append('scripts/ocr')
    from perspective_aligner import align_scoresheet, extract_tables

    warped = align_scoresheet('Hoja de anotaciones.jpg')
    r_table, _ = extract_tables(warped)

    cands_a = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
    cands_b = ['1', '3', '5', '11', '14', '23']

    result = read_running_score(r_table, cands_a, cands_b)
    print(f"\n==========================================")
    print(f"MARCADOR FINAL DETECTADO:")
    print(f"EQUIPO A: {result['score_a']} PUNTOS")
    print(f"EQUIPO B: {result['score_b']} PUNTOS")
    print(f"==========================================")
    print("\nTotal canastas Equipo A:", len(result['baskets_a']))
    print("Total canastas Equipo B:", len(result['baskets_b']))
    print("\nPuntos por Jugador Equipo A:", result['points_a'])
    print("Puntos por Jugador Equipo B:", result['points_b'])
