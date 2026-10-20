"""
Módulo de Alineación y Rectificación de Perspectiva para Actas FIBA.
Endereza la hoja completa a un lienzo canónico de 2400 x 3200 píxeles
y localiza las sub-tablas de Tanteo Corrido y Rosters/Faltas.
"""

import cv2
import numpy as np

CANONICAL_WIDTH = 2400
CANONICAL_HEIGHT = 3200

def order_points(pts):
    """Ordena 4 puntos en orden: top-left, top-right, bottom-right, bottom-left."""
    rect = np.zeros((4, 2), dtype="float32")
    s = pts.sum(axis=1)
    rect[0] = pts[np.argmin(s)]
    rect[2] = pts[np.argmax(s)]

    diff = np.diff(pts, axis=1)
    rect[1] = pts[np.argmin(diff)]
    rect[3] = pts[np.argmax(diff)]
    return rect

def align_scoresheet(image_path_or_array, target_w=CANONICAL_WIDTH, target_h=CANONICAL_HEIGHT):
    """
    Detecta la hoja de papel y aplica Warp Perspective a resolución estándar 2400 x 3200.
    Soporta rutas de imagen (JPG, PNG, WEBP), documentos PDF (vía pypdfium2) o arrays NumPy BGR.
    """
    if isinstance(image_path_or_array, str):
        is_pdf = image_path_or_array.lower().endswith('.pdf')
        if not is_pdf:
            try:
                with open(image_path_or_array, 'rb') as f:
                    header = f.read(5)
                    if header.startswith(b'%PDF-'):
                        is_pdf = True
            except Exception:
                pass

        if is_pdf:
            import pypdfium2 as pdfium
            pdf = pdfium.PdfDocument(image_path_or_array)
            pil_img = pdf[0].render(scale=3).to_pil()
            img = cv2.cvtColor(np.array(pil_img), cv2.COLOR_RGB2BGR)
        else:
            img = cv2.imread(image_path_or_array)

        if img is None:
            raise FileNotFoundError(f"No se pudo cargar la imagen o PDF: {image_path_or_array}")
    else:
        img = image_path_or_array.copy()

    orig_h, orig_w = img.shape[:2]

    # Redimensionar para análisis rápido
    proc_h = 1000
    ratio = orig_h / float(proc_h)
    proc_w = int(orig_w / ratio)
    resized = cv2.resize(img, (proc_w, proc_h))

    gray = cv2.cvtColor(resized, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (7, 7), 0)

    # 1. Detectar el rectángulo exterior de la hoja de papel blanca
    _, paper_mask = cv2.threshold(blurred, 130, 255, cv2.THRESH_BINARY)
    contours, _ = cv2.findContours(paper_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)

    sheet_contour = None
    for c in contours[:5]:
        area = cv2.contourArea(c)
        peri = cv2.arcLength(c, True)
        approx = cv2.approxPolyDP(c, 0.02 * peri, True)
        if len(approx) == 4 and area > (proc_w * proc_h * 0.40):
            sheet_contour = approx
            break

    # 2. Si no se detectó el papel, buscar contorno mediante Canny
    if sheet_contour is None:
        edged = cv2.Canny(blurred, 40, 140)
        c_canny, _ = cv2.findContours(edged, cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
        c_canny = sorted(c_canny, key=cv2.contourArea, reverse=True)[:10]
        for c in c_canny:
            peri = cv2.arcLength(c, True)
            approx = cv2.approxPolyDP(c, 0.02 * peri, True)
            if len(approx) == 4 and cv2.contourArea(c) > (proc_w * proc_h * 0.40):
                sheet_contour = approx
                break

    # 3. Aplicar homografía
    if sheet_contour is not None:
        pts = sheet_contour.reshape(4, 2) * ratio
        rect = order_points(pts)

        dst = np.array([
            [0, 0],
            [target_w - 1, 0],
            [target_w - 1, target_h - 1],
            [0, target_h - 1]
        ], dtype="float32")

        M = cv2.getPerspectiveTransform(rect, dst)
        warped = cv2.warpPerspective(img, M, (target_w, target_h))
    else:
        warped = cv2.resize(img, (target_w, target_h))

    return warped

def extract_tables(warped_img):
    """
    Localiza y extrae las dos tablas principales del acta canónica:
    1. Tabla de Tanteo Corrido (derecha)
    2. Tabla de Rosters y Faltas (izquierda)
    """
    h, w = warped_img.shape[:2]
    gray = cv2.cvtColor(warped_img, cv2.COLOR_BGR2GRAY)
    thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)

    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (int(w / 30), 1))
    v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(h / 30)))

    h_lines = cv2.dilate(cv2.erode(thresh, h_kernel), h_kernel)
    v_lines = cv2.dilate(cv2.erode(thresh, v_kernel), v_kernel)
    table_grid = h_lines + v_lines

    contours, _ = cv2.findContours(table_grid, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    running_score_crop = None
    rosters_crop = None

    # Fallback con coordenadas relativas canónicas comprobadas
    default_running_bbox = (int(w * 0.505), int(h * 0.193), int(w * 0.465), int(h * 0.669))
    default_rosters_bbox = (int(w * 0.027), int(h * 0.196), int(w * 0.473), int(h * 0.646))

    for c in contours:
        bx, by, bw, bh = cv2.boundingRect(c)
        area = bw * bh
        # Buscar tabla derecha (Tanteo Corrido): x > w*0.45, bh > h*0.5
        if area > (w * h * 0.15) and bx > (w * 0.4) and bh > (h * 0.5):
            running_score_crop = warped_img[by:by+bh, bx:bx+bw]
        # Buscar tabla izquierda (Rosters): x < w*0.2, bh > h*0.5
        elif area > (w * h * 0.15) and bx < (w * 0.2) and bh > (h * 0.5):
            rosters_crop = warped_img[by:by+bh, bx:bx+bw]

    if running_score_crop is None:
        rx, ry, rw, rh = default_running_bbox
        running_score_crop = warped_img[ry:ry+rh, rx:rx+rw]

    if rosters_crop is None:
        lx, ly, lw, lh = default_rosters_bbox
        rosters_crop = warped_img[ly:ly+lh, lx:lx+lw]

    return running_score_crop, rosters_crop
