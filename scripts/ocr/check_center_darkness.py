import cv2
import numpy as np

for i in range(1, 6):
    box = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box{i}.jpg')
    gray = cv2.cvtColor(box, cv2.COLOR_BGR2GRAY)
    
    # Exclude borders completely: margin 8px top/bottom, 8px left/right
    center = gray[8:-8, 8:-8]
    
    # Paper background is roughly 160-200.
    # Pen strokes are dark (< 110).
    dark_pixels = np.count_nonzero(center < 115)
    pct = (dark_pixels / center.size) * 100
    min_val = np.min(center)
    
    print(f"Box {i}: Min intensity = {min_val}, Dark pixels (<115) = {dark_pixels} ({pct:.1f}%)")
