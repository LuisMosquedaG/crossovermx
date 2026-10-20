import cv2

roster_a = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg')
foul_cols = [(645, 715), (715, 772), (772, 835), (835, 890), (890, 940)]

# Let's save all 5 boxes of row 0 (Fila 1) and row 1 (Fila 2)
for f_idx, (fx1, fx2) in enumerate(foul_cols):
    box = roster_a[82:146, fx1:fx2]
    cv2.imwrite(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box{f_idx+1}.jpg', box)

print("Saved foul boxes for row 1")
