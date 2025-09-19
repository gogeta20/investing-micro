from django.urls import path

from myproject.core.infrastructure.controllers.health.health_check_controller import HealthCheckController
from myproject.core.infrastructure.controllers.stock.get_stock_by_symbol_controller import GetStockBySymbolController
from myproject.core.infrastructure.controllers.stock.get_stock_controller import GetStockController
from myproject.core.infrastructure.controllers.stock.post_stock_snapshot_controller import PostStockSnapshotController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),  # Ruta específica del health check
    path('stocks/', GetStockController.as_view(), name='get_stock'), # obtencion de stocks
    path('stocks/<str:text>', GetStockBySymbolController.as_view(), name='get_stock_by_symbol'), # obtencion de stocks por el simbolo
    path('stock/snapshot/save', PostStockSnapshotController.as_view(), name='post_stock_snapshot'), # obtencion de stocks por el simbolo
    # path('voice/process/<str:text>/', VoicePokemonController.as_view(), name='voice'),
    # path('sync/database/', SyncDatabaseController.as_view(), name='syncDB'),
    # path('logs/<str:text>/', LogsController.as_view(), name='Logs'),
    # path('detected/intent/<str:text>/', DetectIntentController.as_view(), name='Logs')
]
