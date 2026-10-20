import cv2
import numpy as np
from perspective_aligner import align_scoresheet

aligned = align_scoresheet('c:/Users/luism/gemini-work/sistemaTorneos/Hoja de anotaciones.jpg')
h, w = aligned.shape[:2]

# Let's inspect Roster A
# In canonical 2000x2800:
# Team A table Y is roughly from 460 to 1260 (height 800)
# Team B table Y is roughly from 1270 to 2070 (height 800)
# Roster X is from ~40 to ~980

# Inside the roster table:
# Header is rows 0 to 75
# Then 12 player rows, each ~64px height.
# In each row, the 5 foul boxes are at the right side of the roster:
# Let's find the exact X coordinates for Foul 1, 2, 3, 4, 5.

roster_a = aligned[460:1260, 40:980]
gray_a = cv2.cvtColor(roster_a, cv2.COLOR_BGR2GRAY)

print("Roster A gray shape:", gray_a.shape)
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_roster_a_exact.jpg', roster_a)
