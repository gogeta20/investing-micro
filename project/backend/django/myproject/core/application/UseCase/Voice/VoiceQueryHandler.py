from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
from myproject.core.infrastructure.repository.mongo.mongo_repository import MongoRepository
from myproject.core.Domain.Model.Pokemon import Pokemon

from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
# import kagglehub

class VoiceQueryHandler(QueryHandler):
    def __init__(self):
        self.vectorizer = CountVectorizer()
        self.classifier = MultinomialNB()
        # try:
        mongo_repo = MongoRepository()
        intents_collection = mongo_repo.get_collection('intents')
        data = list(intents_collection.find({}, {'_id': 0, 'frase': 1,'intencion': 1}))
        mongo_repo.close_connection()

        self.train_data = [item['frase'] for item in data]
        self.train_labels = [item['intencion'] for item in data]

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
        if intent == "info":
            return f"""
            SELECT p.*, s.*
            FROM pokemon p
            JOIN pokemon_stats s ON p.id = s.pokemon_id
            WHERE p.name = '{pokemon_name}'
            """
        elif intent == "compare":
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

        pokemon_list = [pokemon.nombre.lower() for pokemon in Pokemon.objects.all()]
        found_pokemon = []

        for pokemon in pokemon_list:
            if pokemon in text.lower():
                found_pokemon.append(pokemon)

        if len(found_pokemon) == 2:
            return f"{found_pokemon[0]},{found_pokemon[1]}"

        elif len(found_pokemon) == 1:
            return found_pokemon[0]

        return "unknown"
