import cv2
import numpy as np

img = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/aligned_scoresheet.jpg')
h, w = img.shape[:2]
print(f"Canvas size: {w} x {h}")

# In the canonical 2000x2800 image:
# Let's crop Section 3:
# Left side (Rosters): X from roughly 0 to 950 (or 1000)
# Right side (Running score): X from roughly 950 to 1950
# Y from roughly 500 to 2200

# Let's save crops to verify:
roster_a = img[int(h * 0.16):int(h * 0.45), int(w * 0.02):int(w * 0.49)]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg', roster_a)

roster_b = img[int(h * 0.45):int(h * 0.75), int(w * 0.02):int(w * 0.49)]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_b.jpg', roster_b)

running_score = img[int(h * 0.16):int(h * 0.75), int(w * 0.49):int(w * 0.98)]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_running_score.jpg', running_score)

print(f"Roster A shape: {roster_a.shape}")
print(f"Roster B shape: {roster_b.shape}")
print(f"Running score shape: {running_score.shape}")
