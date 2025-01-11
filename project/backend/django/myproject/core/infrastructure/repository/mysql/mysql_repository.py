from myproject.core.Domain.Model.Pokemon import Pokemon

class PokemonRepository:
    @staticmethod
    def get_all_pokemon_names():
        return [pokemon.nombre.lower() for pokemon in Pokemon.objects.all()]
