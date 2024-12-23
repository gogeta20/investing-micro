from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
from joblib import dump, load
import os
import json
from django.conf import settings
from myproject.shared.domain.services.IVoiceMLService import IVoiceMLService

class VoiceMLService(IVoiceMLService):
    def __init__(self, model_path, intents_path):
        self.model_path = model_path
        self.intents_path = intents_path
        self.vectorizer = None
        self.classifier = None
        self._load_or_train_model()

    def _load_or_train_model(self):
        if os.path.exists(self.model_path):
            self.vectorizer, self.classifier = load(self.model_path)
        else:
            self.vectorizer = CountVectorizer()
            self.classifier = MultinomialNB()

            with open(self.intents_path, 'r') as file:
                data = json.load(file)

            train_data = [item['frase'] for item in data]
            train_labels = [item['intencion'] for item in data]

            X = self.vectorizer.fit_transform(train_data)
            self.classifier.fit(X, train_labels)

            dump((self.vectorizer, self.classifier), self.model_path)

    def predict(self, text: str):
        """
        Predice la intención a partir de un texto.
        """
        X = self.vectorizer.transform([text])
        intent = self.classifier.predict(X)[0]
        confidence = max(self.classifier.predict_proba(X)[0])
        return intent, confidence
