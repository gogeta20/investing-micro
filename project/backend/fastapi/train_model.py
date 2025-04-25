import json
import os
from joblib import dump
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

DATA_PATH = "data/intents.json"
MODEL_PATH = "model/intent_model.joblib"

os.makedirs("model", exist_ok=True)

with open(DATA_PATH, "r", encoding="utf-8") as file:
    data = json.load(file)

frases = [item["frase"] for item in data]
intenciones = [item["intencion"] for item in data]

vectorizer = CountVectorizer()
X = vectorizer.fit_transform(frases)

model = MultinomialNB()
model.fit(X, intenciones)

dump((vectorizer, model), MODEL_PATH)

print(f"Modelo entrenado y guardado en {MODEL_PATH}")
