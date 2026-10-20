import base64
import json
import urllib.request

api_key = "AIzaSyAfAfmN-TQuL2kIqv-38NiRzgEy71voPUk"

for i in range(1, 6):
    path = f"c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/foul_row1_box{i}.jpg"
    with open(path, "rb") as f:
        b64 = base64.b64encode(f.read()).decode("utf-8")
    
    url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={api_key}"
    payload = {
        "contents": [{
            "parts": [
                {"text": "¿Qué hay en esta casilla de falta? ¿Está vacía, o hay una marca manuscrita (letra P, raya, cruz)? Responde en 1 línea."},
                {"inline_data": {"mime_type": "image/jpeg", "data": b64}}
            ]
        }]
    }
    req = urllib.request.Request(url, data=json.dumps(payload).encode("utf-8"), headers={"Content-Type": "application/json"})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode("utf-8"))
            print(f"Box {i}:", data["candidates"][0]["content"]["parts"][0]["text"].strip())
    except Exception as e:
        print(f"Box {i} error:", e)
