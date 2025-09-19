from django.urls import path

# from myproject.core.infrastructure.controllers.detectIntent.detect_intent_controller import DetectIntentController
from myproject.core.infrastructure.controllers.health.health_check_controller import HealthCheckController
from myproject.core.infrastructure.controllers.stock.get_stock_controller import GetStockController

# from myproject.core.infrastructure.controllers.logs.logs_controller import LogsController
# from myproject.core.infrastructure.controllers.syncDB.SyncDatabaseController import SyncDatabaseController
# from myproject.core.infrastructure.controllers.voice.voice_controller import VoicePokemonController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),  # Ruta específica del health check
    path('stocks/', GetStockController.as_view(), name='get_stock'),  # Ruta específica del health check
    # path('voice/process/<str:text>/', VoicePokemonController.as_view(), name='voice'),
    # path('sync/database/', SyncDatabaseController.as_view(), name='syncDB'),
    # path('logs/<str:text>/', LogsController.as_view(), name='Logs'),
    # path('detected/intent/<str:text>/', DetectIntentController.as_view(), name='Logs')
]
