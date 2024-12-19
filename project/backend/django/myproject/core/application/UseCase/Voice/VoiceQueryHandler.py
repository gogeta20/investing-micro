from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
import kagglehub
import pandas as pd

class VoiceQueryHandler(QueryHandler):
    def __init__(self):
        self.vectorizer = CountVectorizer()
        self.classifier = MultinomialNB()
        # try:
        path = kagglehub.dataset_download("trainingdatapro/llm-dataset")
        df = pd.read_csv(f"{path}/LLM__data.csv")

        spanish_data = df[df['from_language'] == 'es']
        spanish_data = spanish_data.dropna(subset=['text'])
        self.train_data = spanish_data['text'].astype(str).tolist()
        self.train_labels = ["get_pokemon_info"] * len(self.train_data)

        X = self.vectorizer.fit_transform(self.train_data)
        self.classifier.fit(X, self.train_labels)

    def process_text(self, text):
        text = text.lower()
        X = self.vectorizer.transform([text])
        predicted_intent = self.classifier.predict(X)[0]
        pokemon_name = self.extract_pokemon_name(text)
        matched_pattern = None
        confidence = max(self.classifier.predict_proba(X)[0])

        sql_query = self.generate_sql(predicted_intent, pokemon_name)

        return {
            "intent": predicted_intent,
            "pokemon": pokemon_name or "unknown",
            "command_pattern": matched_pattern or "none",
            "confidence": confidence,
            "sql_query": sql_query
        }

    def generate_sql(self, intent, pokemon_name):
        if intent == "get_pokemon_info":
            return f"""
            SELECT p.*, s.*
            FROM pokemon p
            JOIN pokemon_stats s ON p.id = s.pokemon_id
            WHERE p.name = '{pokemon_name}'
            """
        elif intent == "compare_pokemon":

            return f"""
            SELECT p.name, s.*
            FROM pokemon p
            JOIN pokemon_stats s ON p.id = s.pokemon_id
            WHERE p.name IN ('{pokemon_name}', '{pokemon_name}')
            """

        return None

    def handle(self, query: VoiceQuery):
        text = query.get_text()

        ml_result = self.process_text(text)

        return BaseResponse(
            data={
                "status": "OK",
                "intent": ml_result["intent"],
                "pokemon": ml_result["pokemon"],
                "confidence": ml_result["confidence"],
                "sql": ml_result["sql_query"]
                # "kaggle" : path
            },
            message='success request',
            status=200
        ).to_dict()


    def extract_pokemon_name(self, text):
        pokemon_list = ["pikachu", "charizard", "bulbasaur"]  # Lista de todos los pokemon
        for pokemon in pokemon_list:
            if pokemon in text.lower():
                return pokemon

        keywords = ["de", "sobre", "a", "del pokemon"]
        for keyword in keywords:
            if keyword in text:
                parts = text.split(keyword)
                if len(parts) > 1:
                    return parts[1].strip()

        return "unknown"
