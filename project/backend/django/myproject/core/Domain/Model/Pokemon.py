from django.db import models

class Pokemon(models.Model):
    numero_pokedex = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=15)
    peso = models.FloatField()
    altura = models.FloatField()

    class Meta:
        db_table = 'pokemon'  # Vincula este modelo a la tabla existente
