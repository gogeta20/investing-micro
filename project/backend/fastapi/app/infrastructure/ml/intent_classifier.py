import os
# import json
# from sklearn.feature_extraction.text import CountVectorizer
# from sklearn.naive_bayes import MultinomialNB
from joblib import load
# from app.shared.models import ChatRequest, ChatResponse

MODEL_PATH = os.getenv("INTENT_MODEL_PATH", "model/intent_model.joblib")

class IntentClassifierService:
    def __init__(self):
        self.vectorizer = None
        self.classifier = None
        self._load_model()

    def _load_model(self):
        if not os.path.exists(MODEL_PATH):
            raise FileNotFoundError(f"Modelo no encontrado: {MODEL_PATH}")
        self.vectorizer, self.classifier = load(MODEL_PATH)

    def predict(self, message: str):
        X = self.vectorizer.transform([message])
        intent = self.classifier.predict(X)[0]
        confidence = max(self.classifier.predict_proba(X)[0])
        return intent, confidence
