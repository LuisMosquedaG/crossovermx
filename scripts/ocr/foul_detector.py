"""
Módulo de Detección Óptica de Marcas (OMR) de Faltas Personales en Actas FIBA.
Analiza las casillas de faltas (1 a 5) de cada jugador de Equipo A y Equipo B,
diferenciando marcas de falta ('P', 'P2', 'T', etc.) de líneas de anulación '-' o casillas vacías.
"""

import cv2
import numpy as np

# Coordenadas exactas medidas en resolución canónica 1140x2070
FOUL_COLUMNS = [
    (810, 856),   # Falta 1
    (864, 912),   # Falta 2
    (920, 969),   # Falta 3
    (977, 1024),  # Falta 4
    (1032, 1080)  # Falta 5
]

JERSEY_COL = (686, 743)

def is_foul_marked(box_img):
    """
    Determina si una casilla individual contiene una falta registrada.
    Una falta ('P', 'P1', 'P2', 'T', 'D') tiene extensión vertical notable (altura >= 12px y área >= 50px).
    Un guion horizontal '-' tiene altura pequeña (<= 5px).
    Una casilla vacía no tiene tinta significativa.
    """
    if box_img.shape[0] < 12 or box_img.shape[1] < 12:
        return False
        
    gray = cv2.cvtColor(box_img, cv2.COLOR_BGR2GRAY)
    bg = np.median(gray)
    thresh = (gray < (bg - 28)).astype(np.uint8) * 255

    # Limpiar márgenes de 3px para desconectar bordes de cuadrícula
    thresh[:3, :] = 0
    thresh[-3:, :] = 0
    thresh[:, :3] = 0
    thresh[:, -3:] = 0

    num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(thresh)
    max_h = 0
    max_area = 0
    for j in range(1, num_labels):
        h = stats[j, cv2.CC_STAT_HEIGHT]
        area = stats[j, cv2.CC_STAT_AREA]
        if area > max_area:
            max_area = area
        if h > max_h:
            max_h = h

    return (max_h >= 12 and max_area >= 50)

def detect_team_fouls(rosters_img, team='A'):
    """
    Detecta las faltas de cada jugador (hasta 12 filas) para Equipo A o Equipo B.
    Devuelve lista de diccionarios por jugador.
    """
    h_img, w_img = rosters_img.shape[:2]

    scale_y = h_img / 2070.0
    scale_x = w_img / 1140.0

    if team.upper() == 'A':
        start_y = int(348 * scale_y)
        row_h = 49.0 * scale_y
    else:
        start_y = int(1370 * scale_y)
        row_h = 48.5 * scale_y

    players = []

    for r in range(12):
        y1 = int(start_y + r * row_h)
        y2 = int(start_y + (r + 1) * row_h)

        if y2 > h_img:
            break

        # Comprobar si la fila tiene jugador (celda de dorsal con tinta)
        jx1 = int(JERSEY_COL[0] * scale_x)
        jx2 = int(JERSEY_COL[1] * scale_x)
        dorsal_cell = rosters_img[y1:y2, jx1:jx2]

        gray_d = cv2.cvtColor(dorsal_cell, cv2.COLOR_BGR2GRAY)
        bg_d = np.median(gray_d)
        d_ink = np.count_nonzero(gray_d[4:-4, 4:-4] < (bg_d - 25))

        # Al menos 25 píxeles oscuros en la casilla de dorsal
        has_player = (d_ink >= 25)
        if not has_player:
            continue

        # Contar faltas en las 5 casillas
        foul_count = 0
        foul_details = []

        for f_idx, (fx1_nom, fx2_nom) in enumerate(FOUL_COLUMNS):
            fx1 = int(fx1_nom * scale_x)
            fx2 = int(fx2_nom * scale_x)

            # Tomar celda con margen de seguridad vertical
            box = rosters_img[y1+5:y2-5, fx1:fx2]
            marked = is_foul_marked(box)
            if marked:
                foul_count += 1
            foul_details.append(marked)

        players.append({
            'row_index': r,
            'dorsal_crop': dorsal_cell,
            'fouls': foul_count,
            'foul_boxes': foul_details
        })

    return players

if __name__ == "__main__":
    import sys
    sys.path.append('scripts/ocr')
    from perspective_aligner import align_scoresheet, extract_tables

    warped = align_scoresheet('Hoja de anotaciones.jpg')
    _, rosters = extract_tables(warped)

    print("=== FALTAS DETECTADAS - EQUIPO A ===")
    team_a = detect_team_fouls(rosters, 'A')
    for p in team_a:
        print(f"Fila {p['row_index']+1:2d}: Faltas = {p['fouls']} {p['foul_boxes']}")

    print("\n=== FALTAS DETECTADAS - EQUIPO B ===")
    team_b = detect_team_fouls(rosters, 'B')
    for p in team_b:
        print(f"Fila {p['row_index']+1:2d}: Faltas = {p['fouls']} {p['foul_boxes']}")
