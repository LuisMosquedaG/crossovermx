import base64
import json
import urllib.request

api_key = "AIzaSyAfAfmN-TQuL2kIqv-38NiRzgEy71voPUk"

with open("c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/roster_a_header.jpg", "rb") as f:
    b64 = base64.b64encode(f.read()).decode("utf-8")

url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={api_key}"
payload = {
    "contents": [{
        "parts": [
            {"text": "Transcribe exactamente los títulos impresos de cada una de las columnas en este encabezado de tabla de jugadores, de izquierda a derecha."},
            {"inline_data": {"mime_type": "image/jpeg", "data": b64}}
        ]
    }]
}
req = urllib.request.Request(url, data=json.dumps(payload).encode("utf-8"), headers={"Content-Type": "application/json"})
with urllib.request.urlopen(req, timeout=10) as resp:
    data = json.loads(resp.read().decode("utf-8"))
    print(data["candidates"][0]["content"]["parts"][0]["text"])
