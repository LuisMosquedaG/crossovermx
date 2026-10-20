import cv2

roster_a = cv2.imread('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/crop_aligned_roster_a.jpg')
header = roster_a[0:82, :]
cv2.imwrite('c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_a_header.jpg', header)
print("Saved roster_a_header.jpg")
