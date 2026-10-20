import cv2
import numpy as np

box1 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box1.jpg')
hsv1 = cv2.cvtColor(box1, cv2.COLOR_BGR2HSV)

# Find pixels that deviate from gray
s = hsv1[:, :, 1]
v = hsv1[:, :, 2]
h = hsv1[:, :, 0]

print("Max Saturation in Box 1:", np.max(s))
print("Mean Saturation in Box 1:", np.mean(s))

# Find the 10 most saturated pixels
top_idx = np.argsort(s.flatten())[-10:]
for idx in top_idx:
    r, c = np.unravel_index(idx, s.shape)
    print(f"Pixel at ({r}, {c}): BGR = {box1[r, c]}, HSV = {hsv1[r, c]}")
