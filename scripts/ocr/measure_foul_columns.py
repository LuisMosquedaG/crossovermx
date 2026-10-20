import cv2
import numpy as np

img = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_roster_a_exact.jpg')
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
h, w = gray.shape

# Crop right side where foul boxes are
right_side = img[:, 500:940]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_roster_right_side.jpg', right_side)

# Vertical line detection in right side
gray_r = gray[:, 500:940]
thresh = cv2.adaptiveThreshold(~gray_r, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 15, -2)
v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, int(h * 0.3)))
v_lines = cv2.dilate(cv2.erode(thresh, v_kernel), v_kernel)

col_sums = np.sum(v_lines, axis=0)
v_peaks = []
for x in range(1, gray_r.shape[1] - 1):
    if col_sums[x] > (h * 255 * 0.25) and col_sums[x] >= col_sums[x - 1] and col_sums[x] >= col_sums[x + 1]:
        v_peaks.append(x)

filtered = []
for vp in v_peaks:
    if not filtered or (vp - filtered[-1] > 25):
        filtered.append(vp)

print("Detected foul columns in right side (relative to x=500):", filtered)
print("Absolute X positions in roster:", [f + 500 for f in filtered])
