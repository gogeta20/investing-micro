from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB


class IntentClassifier:
    def __init__(self):
        self.vectorizer = CountVectorizer()
        self.classifier = MultinomialNB()

        # Entrenar con ejemplos
        examples = [
            "muéstrame a pikachu",
            "quiero ver los datos de pikachu",
            "busca a pikachu",
            "información de pikachu"
        ]
        intents = ["get_pokemon_info"] * len(examples)

        # Entrenar el clasificador
        X = self.vectorizer.fit_transform(examples)
        self.classifier.fit(X, intents)

    def get_intent(self, text):
        X = self.vectorizer.transform([text])
        return self.classifier.predict(X)[0]
