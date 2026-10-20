import cv2
import numpy as np

box3 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box3.jpg')
gray = cv2.cvtColor(box3, cv2.COLOR_BGR2GRAY)

# Check vertical projection of dark pixels across X axis
dark_mask = (gray < 115).astype(np.uint8)
x_proj = np.sum(dark_mask, axis=0)
print("Dark pixels per column X in Box 3 (shape:", box3.shape, "):")
for x, val in enumerate(x_proj):
    if val > 10:
        print(f" - X={x}: {val} dark pixels")
