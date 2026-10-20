import cv2
import numpy as np

box1 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box1.jpg')
box2 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box2.jpg')

# Convert to HSV
hsv1 = cv2.cvtColor(box1, cv2.COLOR_BGR2HSV)
hsv2 = cv2.cvtColor(box2, cv2.COLOR_BGR2HSV)

# Red mask: Hue 0-10 or 170-180, Saturation > 50, Value > 50
red_mask1_a = cv2.inRange(hsv1, np.array([0, 50, 50]), np.array([12, 255, 255]))
red_mask1_b = cv2.inRange(hsv1, np.array([168, 50, 50]), np.array([180, 255, 255]))
red1 = red_mask1_a | red_mask1_b

red_mask2_a = cv2.inRange(hsv2, np.array([0, 50, 50]), np.array([12, 255, 255]))
red_mask2_b = cv2.inRange(hsv2, np.array([168, 50, 50]), np.array([180, 255, 255]))
red2 = red_mask2_a | red_mask2_b

# Blue mask: Hue 95-135, Saturation > 50, Value > 50
blue1 = cv2.inRange(hsv1, np.array([95, 50, 50]), np.array([135, 255, 255]))
blue2 = cv2.inRange(hsv2, np.array([95, 50, 50]), np.array([135, 255, 255]))

ink1 = red1 | blue1
ink2 = red2 | blue2

print(f"Box 1 (has 'P'): Ink pixels = {np.count_nonzero(ink1)} ({np.count_nonzero(ink1)/ink1.size*100:.2f}%)")
print(f"Box 2 (empty):   Ink pixels = {np.count_nonzero(ink2)} ({np.count_nonzero(ink2)/ink2.size*100:.2f}%)")
