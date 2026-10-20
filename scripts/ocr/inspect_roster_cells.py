import cv2
import numpy as np

img = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg')

# Let's crop row 1 (Player 1: Joshua #5) and row 2 (Samuel #14)
# Y from 82 to 146 (row 1) and 146 to 211 (row 2)
row1 = img[82:146, :]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_row1.jpg', row1)

row2 = img[146:211, :]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_row2.jpg', row2)

print("Saved test_row1.jpg and test_row2.jpg")
