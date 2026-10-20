import cv2
import numpy as np

img = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg')
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
h, w = gray.shape

# Threshold
thresh = cv2.adaptiveThreshold(~gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)

# Horizontal lines
h_structure = cv2.getStructuringElement(cv2.MORPH_RECT, (int(w / 10), 1))
h_lines = cv2.dilate(cv2.erode(thresh, h_structure), h_structure)

# Sum across rows to find row line coordinates
row_sums = np.sum(h_lines, axis=1)
peaks = []
for y in range(1, h - 1):
    if row_sums[y] > (w * 255 * 0.25) and row_sums[y] >= row_sums[y - 1] and row_sums[y] >= row_sums[y + 1]:
        peaks.append(y)

# Filter peaks that are too close
filtered_peaks = []
for p in peaks:
    if not filtered_peaks or (p - filtered_peaks[-1] > 15):
        filtered_peaks.append(p)

print(f"Detected {len(filtered_peaks)} row divider lines in Roster A:")
print(filtered_peaks)

# Vertical lines
v_structure = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(h / 15)))
v_lines = cv2.dilate(cv2.erode(thresh, v_structure), v_structure)
col_sums = np.sum(v_lines, axis=0)
v_peaks = []
for x in range(1, w - 1):
    if col_sums[x] > (h * 255 * 0.20) and col_sums[x] >= col_sums[x - 1] and col_sums[x] >= col_sums[x + 1]:
        v_peaks.append(x)

filtered_v_peaks = []
for vp in v_peaks:
    if not filtered_v_peaks or (vp - filtered_v_peaks[-1] > 20):
        filtered_v_peaks.append(vp)

print(f"Detected {len(filtered_v_peaks)} column divider lines in Roster A:")
print(filtered_v_peaks)
