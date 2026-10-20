import os
import cv2
import numpy as np

class NeuralDigitClassifier:
    def __init__(self):
        model_path = "c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/digit_model.npz"
        w = np.load(model_path)
        self.W1 = w['W1']
        self.b1 = w['b1']
        self.W2 = w['W2']
        self.b2 = w['b2']
        
    def get_probs(self, binary_img):
        """Preprocesa un recorte binario y devuelve vector de probabilidades (10,)."""
        pts = cv2.findNonZero(binary_img)
        if pts is None:
            return np.ones(10, dtype=np.float32) / 10.0
        x, y, w, h = cv2.boundingRect(pts)
        crop = binary_img[y:y+h, x:x+w]
        
        canvas = np.zeros((28, 28), dtype=np.uint8)
        scale = 20.0 / max(w, h)
        nw, nh = max(1, int(w * scale)), max(1, int(h * scale))
        resized = cv2.resize(crop, (nw, nh), interpolation=cv2.INTER_AREA)
        ox = (28 - nw) // 2
        oy = (28 - nh) // 2
        canvas[oy:oy+nh, ox:ox+nw] = resized
        
        x_vec = canvas.flatten().reshape(1, -1).astype(np.float32) / 255.0
        z1 = np.dot(x_vec, self.W1) + self.b1
        a1 = np.maximum(0, z1)
        z2 = np.dot(a1, self.W2) + self.b2
        exp_z = np.exp(z2 - np.max(z2))
        probs = exp_z / np.sum(exp_z)
        return probs.flatten()

    def clean_jersey(self, img):
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        bg = np.median(gray)
        thresh = (gray < (bg - 18)).astype(np.uint8) * 255
        thresh[:2, :] = 0; thresh[-2:, :] = 0
        thresh[:, :2] = 0; thresh[:, -2:] = 0
        
        # Remove horizontal underline lines
        h_k = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 1))
        h_lines = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, h_k)
        thresh = cv2.subtract(thresh, h_lines)
        
        k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
        thresh = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, k)
        
        pts = cv2.findNonZero(thresh)
        if pts is None: return None
        x, y, w, h = cv2.boundingRect(pts)
        return thresh[y:y+h, x:x+w]

    def predict_jersey(self, cell_img, candidate_numbers):
        crop = self.clean_jersey(cell_img)
        if crop is None:
            return candidate_numbers[0]
            
        h, w = crop.shape
        aspect = w / float(h)
        
        # Probabilidades para dígito único
        p_full = self.get_probs(crop)
        
        # Probabilidades para 2 dígitos (dividir en col_proj mínimo)
        col_proj = np.sum(crop, axis=0) / 255.0
        mid_s, mid_e = int(w * 0.25), int(w * 0.75)
        if mid_e > mid_s:
            split_x = mid_s + np.argmin(col_proj[mid_s:mid_e])
        else:
            split_x = w // 2
            
        left_crop = crop[:, :split_x]
        right_crop = crop[:, split_x:]
        p_d1 = self.get_probs(left_crop)
        p_d2 = self.get_probs(right_crop)
        
        scores = {}
        for cand in candidate_numbers:
            s_cand = str(cand).strip()
            if len(s_cand) == 1:
                d = int(s_cand)
                # Probabilidad con penalización por aspect
                prob = p_full[d]
                if aspect > 1.35: prob *= 0.1
                scores[s_cand] = prob
            elif len(s_cand) == 2:
                d1 = int(s_cand[0])
                d2 = int(s_cand[1])
                # Media geométrica de probabilidades
                prob = np.sqrt(p_d1[d1] * p_d2[d2])
                if aspect < 0.95: prob *= 0.1
                scores[s_cand] = prob
                
        return max(scores, key=scores.get)

clf = NeuralDigitClassifier()

# Test Team B
print("=== TEAM B (FIXED NEURAL DIGIT RECOGNIZER) ===")
b_expected = {2:'23', 4:'23', 6:'3', 8:'3', 9:'3', 11:'23', 13:'14', 15:'1', 17:'23', 19:'3', 21:'23', 22:'23', 23:'23', 25:'1', 27:'23', 29:'1'}
b_cands = ['1', '3', '5', '11', '14', '23']
b_correct = 0
for r, exp in b_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_b_{r}.jpg')
    pred = clf.predict_jersey(img, b_cands)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: b_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team B Accuracy: {b_correct}/{len(b_expected)} ({b_correct/len(b_expected)*100:.1f}%)")

# Test Team A
print("\n=== TEAM A (FIXED NEURAL DIGIT RECOGNIZER) ===")
a_expected = {2:'5', 4:'91', 6:'14', 8:'14', 10:'33', 11:'30', 12:'33', 13:'33', 15:'91', 17:'91', 19:'55', 21:'17'}
a_cands = ['5', '14', '17', '23', '30', '33', '40', '55', '91']
a_correct = 0
for r, exp in a_expected.items():
    img = cv2.imread(f'c:/Users/luism/gemini-work/sistemaTorneos/scripts/ocr/dorsal_a_{r}.jpg')
    pred = clf.predict_jersey(img, a_cands)
    match = "OK" if pred == exp else f"MISMATCH (got {pred})"
    if pred == exp: a_correct += 1
    print(f"Row {r:2d}: Expected {exp:>2s} -> Predicted {pred:>2s} [{match}]")
print(f"Team A Accuracy: {a_correct}/{len(a_expected)} ({a_correct/len(a_expected)*100:.1f}%)")
