import cv2
import numpy as np

row1 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_row1.jpg')
gray = cv2.cvtColor(row1, cv2.COLOR_BGR2GRAY)
h, w = gray.shape

# Threshold to find vertical dividers inside the row
thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)
v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(h * 0.6)))
v_lines = cv2.dilate(cv2.erode(thresh, v_kernel), v_kernel)

col_sums = np.sum(v_lines, axis=0)
v_peaks = []
for x in range(1, w - 1):
    if col_sums[x] > (h * 255 * 0.4) and col_sums[x] >= col_sums[x - 1] and col_sums[x] >= col_sums[x + 1]:
        v_peaks.append(x)

filtered = []
for vp in v_peaks:
    if not filtered or (vp - filtered[-1] > 15):
        filtered.append(vp)

print(f"Row 1 width: {w}, height: {h}")
print(f"Detected vertical dividers in row 1: {filtered}")
