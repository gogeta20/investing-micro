from django.urls import path
from myproject.core.infrastructure.controllers.health.health_check_controller import HealthCheckController
from myproject.core.infrastructure.controllers.syncDB.SyncDatabaseController import SyncDatabaseController
from myproject.core.infrastructure.controllers.voice.voice_controller import VoicePokemonController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),  # Ruta específica del health check
    path('voice/process/<str:text>/', VoicePokemonController.as_view(), name='voice'),
    path('sync/database/', SyncDatabaseController.as_view(), name='syncDB'),
]
