import cv2

row1 = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/test_row1.jpg')
divs = [8, 120, 242, 358, 483, 599, 645, 721, 776, 843, 940]

for i in range(len(divs) - 1):
    x1, x2 = divs[i], divs[i + 1]
    cell = row1[:, x1:x2]
    cv2.imwrite(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/cell_{i}.jpg', cell)

print(f"Exported {len(divs) - 1} cells for row 1")
