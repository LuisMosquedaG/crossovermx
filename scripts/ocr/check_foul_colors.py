import cv2
import numpy as np

for i in range(1, 6):
    box = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box{i}.jpg')
    print(f"Box {i} shape: {box.shape[1]}x{box.shape[0]}")
    # Mean color (B, G, R)
    mean_b, mean_g, mean_r = cv2.mean(box)[:3]
    print(f" - Mean RGB: ({mean_r:.1f}, {mean_g:.1f}, {mean_b:.1f})")

dorsal0 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_row0.jpg')
print(f"Dorsal 0 shape: {dorsal0.shape[1]}x{dorsal0.shape[0]}")
