import cv2
import numpy as np

for i in range(10):
    cell = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/cell_{i}.jpg')
    gray = cv2.cvtColor(cell, cv2.COLOR_BGR2GRAY)
    # Check ink presence: percentage of dark pixels
    # Otsu threshold
    _, bin_img = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    # Exclude borders (margin 4px)
    inner = bin_img[4:-4, 4:-4]
    dark_pct = (np.count_nonzero(inner) / inner.size) * 100 if inner.size > 0 else 0
    print(f"Cell {i} shape: {cell.shape[1]}x{cell.shape[0]}, Dark pixel %: {dark_pct:.1f}%")
