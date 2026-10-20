import cv2
import numpy as np

img = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_running_score.jpg')
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
h, w = gray.shape
print(f"Running score size: {w} x {h}")

thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)

# Find vertical columns
v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(h * 0.4)))
v_lines = cv2.dilate(cv2.erode(thresh, v_kernel), v_kernel)

col_sums = np.sum(v_lines, axis=0)
v_peaks = []
for x in range(1, w - 1):
    if col_sums[x] > (h * 255 * 0.3) and col_sums[x] >= col_sums[x - 1] and col_sums[x] >= col_sums[x + 1]:
        v_peaks.append(x)

filtered_v = []
for vp in v_peaks:
    if not filtered_v or (vp - filtered_v[-1] > 20):
        filtered_v.append(vp)

print(f"Detected vertical dividers in running score ({len(filtered_v)}):")
print(filtered_v)

# Find horizontal rows
h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (int(w * 0.1), 1))
h_lines = cv2.dilate(cv2.erode(thresh, h_kernel), h_kernel)
row_sums = np.sum(h_lines, axis=1)

r_peaks = []
for y in range(1, h - 1):
    if row_sums[y] > (w * 255 * 0.25) and row_sums[y] >= row_sums[y - 1] and row_sums[y] >= row_sums[y + 1]:
        r_peaks.append(y)

filtered_r = []
for rp in r_peaks:
    if not filtered_r or (rp - filtered_r[-1] > 20):
        filtered_r.append(rp)

print(f"Detected horizontal row lines ({len(filtered_r)}):")
print(filtered_r[:15]) # Print first 15
if len(filtered_r) >= 2:
    print(f"Average row height: {np.mean(np.diff(filtered_r)):.1f} px")
