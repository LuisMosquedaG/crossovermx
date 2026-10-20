import cv2
import numpy as np

header = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_a_header.jpg')
gray = cv2.cvtColor(header, cv2.COLOR_BGR2GRAY)
thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)

# Vertical line detection in header
v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(header.shape[0] * 0.4)))
v_lines = cv2.dilate(cv2.erode(thresh, v_kernel), v_kernel)

col_sums = np.sum(v_lines, axis=0)
peaks = []
for x in range(1, header.shape[1] - 1):
    if col_sums[x] > (header.shape[0] * 255 * 0.3) and col_sums[x] >= col_sums[x - 1] and col_sums[x] >= col_sums[x + 1]:
        peaks.append(x)

filtered = []
for p in peaks:
    if not filtered or (p - filtered[-1] > 10):
        filtered.append(p)

print("Vertical lines in Header (width:", header.shape[1], "):")
print(filtered)
