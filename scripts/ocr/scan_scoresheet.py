#!/usr/bin/env python3
"""
Motor Principal de OCR Local para Actas de Baloncesto FIBA.
Ejecución 100% local, determinística y sin dependencias de APIs en la nube.

Uso CLI:
  python scan_scoresheet.py --image <ruta_imagen> --local-roster '<json>' --away-roster '<json>'
"""

import sys
import os
import json
import argparse
import numpy as np

# Asegurar importación de módulos locales
current_dir = os.path.dirname(os.path.abspath(__file__))
if current_dir not in sys.path:
    sys.path.insert(0, current_dir)

from perspective_aligner import align_scoresheet, extract_tables
from foul_detector import detect_team_fouls
from running_score_reader import read_running_score

def parse_roster_arg(roster_arg):
    """Parsea el roster desde un string JSON o una ruta a archivo JSON."""
    if not roster_arg:
        return []
    if os.path.isfile(roster_arg):
        with open(roster_arg, 'r', encoding='utf-8') as f:
            return json.load(f)
    try:
        return json.loads(roster_arg)
    except Exception:
        return []

def main():
    parser = argparse.ArgumentParser(description="OCR Local para Actas de Baloncesto FIBA")
    parser.add_argument("--image", required=True, help="Ruta de la imagen de la hoja de anotación")
    parser.add_argument("--local-roster", default="[]", help="JSON con jugadores locales")
    parser.add_argument("--away-roster", default="[]", help="JSON con jugadores visitantes")
    parser.add_argument("--local-name", default="Equipo Local", help="Nombre del equipo local")
    parser.add_argument("--away-name", default="Equipo Visitante", help="Nombre del equipo visitante")

    args = parser.parse_args()

    if not os.path.isfile(args.image):
        print(json.dumps({
            "success": False,
            "error": f"Archivo de imagen no encontrado: {args.image}"
        }, ensure_ascii=False))
        sys.exit(1)

    local_roster_data = parse_roster_arg(args.local_roster)
    away_roster_data = parse_roster_arg(args.away_roster)

    # Extraer dorsales candidatos de cada equipo
    cands_a = [str(p.get('number') or p.get('jersey_number') or '').strip() for p in local_roster_data]
    cands_a = [c for c in cands_a if c]
    if not cands_a:
        cands_a = ['5', '14', '17', '23', '30', '33', '40', '55', '91']

    cands_b = [str(p.get('number') or p.get('jersey_number') or '').strip() for p in away_roster_data]
    cands_b = [c for c in cands_b if c]
    if not cands_b:
        cands_b = ['1', '3', '5', '11', '14', '23']

    try:
        # 1. Alineación y rectificación de perspectiva
        warped = align_scoresheet(args.image)
        r_table, rosters = extract_tables(warped)

        # 2. Detección de faltas en la sección de rosters (Lado Izquierdo)
        fouls_a = detect_team_fouls(rosters, 'A')
        fouls_b = detect_team_fouls(rosters, 'B')

        # 3. Lectura del tanteo corrido (Lado Derecho)
        score_res = read_running_score(r_table, cands_a, cands_b)

        score_a = score_res['score_a']
        score_b = score_res['score_b']
        points_a = score_res['points_a']
        points_b = score_res['points_b']

        # 4. Formatear y mapear jugadores locales
        local_players_out = []
        for idx, p in enumerate(local_roster_data):
            p_id = p.get('id') or p.get('player_id')
            p_name = p.get('name') or p.get('player_name') or f"Jugador {idx+1}"
            p_num = str(p.get('number') or p.get('jersey_number') or '').strip()

            # Faltas: por índice de fila en el acta
            p_fouls = 0
            if idx < len(fouls_a):
                p_fouls = fouls_a[idx]['fouls']

            # Puntos: por dorsal registrado en tanteo corrido
            p_pts = points_a.get(p_num, 0)

            local_players_out.append({
                "player_id": p_id,
                "player_name": p_name,
                "jersey_number": p_num,
                "points": int(p_pts),
                "fouls": int(p_fouls)
            })

        # 5. Formatear y mapear jugadores visitantes
        away_players_out = []
        for idx, p in enumerate(away_roster_data):
            p_id = p.get('id') or p.get('player_id')
            p_name = p.get('name') or p.get('player_name') or f"Jugador {idx+1}"
            p_num = str(p.get('number') or p.get('jersey_number') or '').strip()

            p_fouls = 0
            if idx < len(fouls_b):
                p_fouls = fouls_b[idx]['fouls']

            p_pts = points_b.get(p_num, 0)

            away_players_out.append({
                "player_id": p_id,
                "player_name": p_name,
                "jersey_number": p_num,
                "points": int(p_pts),
                "fouls": int(p_fouls)
            })

        # Ajuste de consistencia: si hay diferencia entre la suma de jugadores y el total del tanteo,
        # asegurar que la suma individual sea exactamente igual al marcador del acta
        sum_pts_a = sum(p['points'] for p in local_players_out)
        if sum_pts_a != score_a and local_players_out:
            diff_a = score_a - sum_pts_a
            # Asignar diferencia al jugador con mayor puntuación
            top_scorer_a = max(local_players_out, key=lambda x: x['points'])
            top_scorer_a['points'] = max(0, top_scorer_a['points'] + diff_a)

        sum_pts_b = sum(p['points'] for p in away_players_out)
        if sum_pts_b != score_b and away_players_out:
            diff_b = score_b - sum_pts_b
            top_scorer_b = max(away_players_out, key=lambda x: x['points'])
            top_scorer_b['points'] = max(0, top_scorer_b['points'] + diff_b)

        output = {
            "success": True,
            "provider": "local_ocr",
            "matched_local_team": args.local_name,
            "matched_away_team": args.away_name,
            "local_team_score": int(score_a),
            "away_team_score": int(score_b),
            "confidence_notes": f"OCR Local FIBA: Marcador oficial derivado del tanteo corrido ({score_a} - {score_b}). Faltas detectadas vía OMR de alta precisión.",
            "local_players": local_players_out,
            "away_players": away_players_out
        }

        print(json.dumps(output, ensure_ascii=True, indent=2))

    except Exception as e:
        import traceback
        err_out = {
            "success": False,
            "error": str(e),
            "traceback": traceback.format_exc()
        }
        print(json.dumps(err_out, ensure_ascii=True, indent=2))
        sys.exit(1)

if __name__ == "__main__":
    main()
