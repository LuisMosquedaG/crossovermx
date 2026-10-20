import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont

def render_digit(text, font_path, size=32, blur=1):
    img = Image.new('L', (48, 48), color=0)
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(font_path, size)
    bbox = font.getbbox(text)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    draw.text(((48 - w) // 2 - bbox[0], (48 - h) // 2 - bbox[1]), text, fill=255, font=font)
    arr = np.array(img)
    if blur > 0:
        arr = cv2.GaussianBlur(arr, (3, 3), 0)
    return arr

# Test rendering
f1 = "C:/Windows/Fonts/inkfree.ttf"
f2 = "C:/Windows/Fonts/segoepr.ttf"

for num in ['1', '3', '5', '14', '17', '23', '30', '33', '55', '91']:
    r1 = render_digit(num, f1)
    cv2.imwrite(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/font_ink_{num}.jpg', r1)

print("Rendered test digits with Ink Free font successfully.")
