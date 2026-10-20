import cv2
import numpy as np

def align_document(image_path, target_width=2000, target_height=2800):
    img = cv2.imread(image_path)
    if img is None:
        raise FileNotFoundError(f"Could not load image: {image_path}")

    orig_h, orig_w = img.shape[:2]
    print(f"Original size: {orig_w} x {orig_h}")

    # Downscale for fast contour detection
    downscale_h = 1000
    ratio = orig_h / float(downscale_h)
    downscale_w = int(orig_w / ratio)
    resized = cv2.resize(img, (downscale_w, downscale_h))

    # Preprocessing
    gray = cv2.cvtColor(resized, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edged = cv2.Canny(blurred, 50, 150)

    # Find contours
    contours, _ = cv2.findContours(edged.copy(), cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)[:10]

    sheet_contour = None
    for c in contours:
        peri = cv2.arcLength(c, True)
        approx = cv2.approxPolyDP(c, 0.02 * peri, True)
        area = cv2.contourArea(c)
        # We expect a 4-point contour covering at least 30% of the image
        if len(approx) == 4 and area > (downscale_w * downscale_h * 0.3):
            sheet_contour = approx
            print(f"Found 4-point document contour with area ratio: {area / (downscale_w * downscale_h):.2f}")
            break

    if sheet_contour is None:
        print("No strict 4-corner document contour found; checking table bounding box...")
        # Fallback: table line detection
        thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)
        h_structure = cv2.getStructuringElement(cv2.MORPH_RECT, (int(downscale_w / 20), 1))
        v_structure = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(downscale_h / 20)))
        h_lines = cv2.dilate(cv2.erode(thresh, h_structure), h_structure)
        v_lines = cv2.dilate(cv2.erode(thresh, v_structure), v_structure)
        table = h_lines + v_lines
        
        table_contours, _ = cv2.findContours(table, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        if table_contours:
            largest_c = max(table_contours, key=cv2.contourArea)
            rect = cv2.minAreaRect(largest_c)
            box = cv2.boxPoints(rect)
            sheet_contour = np.int32(box)
            print("Detected outer table boundary via morphological lines.")

    if sheet_contour is not None:
        # Scale back to original resolution
        pts = sheet_contour.reshape(4, 2) * ratio
        
        # Order points: top-left, top-right, bottom-right, bottom-left
        rect = np.zeros((4, 2), dtype="float32")
        s = pts.sum(axis=1)
        rect[0] = pts[np.argmin(s)]
        rect[2] = pts[np.argmax(s)]
        diff = np.diff(pts, axis=1)
        rect[1] = pts[np.argmin(diff)]
        rect[3] = pts[np.argmax(diff)]

        # Warp perspective
        dst = np.array([
            [0, 0],
            [target_width - 1, 0],
            [target_width - 1, target_height - 1],
            [0, target_height - 1]
        ], dtype="float32")

        M = cv2.getPerspectiveTransform(rect, dst)
        warped = cv2.warpPerspective(img, M, (target_width, target_height))
        print(f"Successfully warped to canonical shape: {target_width} x {target_height}")
        return warped
    else:
        print("Using direct resize to canonical shape as fallback.")
        return cv2.resize(img, (target_width, target_height))

if __name__ == "__main__":
    warped = align_document('c:/Users/luism/gemini-work/sistemaTorneos/Hoja de anotaciones.jpg')
    cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/aligned_scoresheet.jpg', warped)
    print("Saved aligned_scoresheet.jpg")
