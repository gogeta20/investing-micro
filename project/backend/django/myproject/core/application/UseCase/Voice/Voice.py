class Voice:
    def __init__(self, ml_service, pokemon_repository):
        self.ml_service = ml_service
        self.pokemon_repository = pokemon_repository

    def execute(self, text):
        ml_result = self.process_text(text)

        return {
            "intent": ml_result["intent"],
            "pokemon": ml_result["pokemon"],
            "confidence": ml_result["confidence"],
            "result": ml_result["sql_query"]
        }

    def process_text(self, text):
        text = text.lower()
        intent, confidence = self.ml_service.predict(text)
        pokemon_name = self.extract_pokemon_name(text)
        sql_query = self.generate_sql(intent, pokemon_name)

        return {
            "intent": intent,
            "pokemon": pokemon_name or "unknown",
            "confidence": confidence,
            "sql_query": sql_query
        }

    def extract_pokemon_name(self, text):
        pokemon_list = self.pokemon_repository.get_all_pokemon_names()
        found_pokemon = [pokemon for pokemon in pokemon_list if pokemon in text.lower()]

        if len(found_pokemon) == 2:
            return f"{found_pokemon[0]},{found_pokemon[1]}"
        elif len(found_pokemon) == 1:
            return found_pokemon[0]
        return "unknown"

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


