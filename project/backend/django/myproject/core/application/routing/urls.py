from django.urls import path

from myproject.core.infrastructure.controllers.health.health_check_controller import HealthCheckController
from myproject.core.infrastructure.controllers.stock.get_current_stocks_controller import GetCurrentStocksController
from myproject.core.infrastructure.controllers.stock.get_daily_analysis_controller import GetDailyAnalysisController
from myproject.core.infrastructure.controllers.stock.get_results_portafolio_controller import GetResultsPortafolioController
from myproject.core.infrastructure.controllers.stock.get_stock_by_symbol_controller import GetStockBySymbolController
from myproject.core.infrastructure.controllers.stock.get_stock_controller import GetStockController
from myproject.core.infrastructure.controllers.stock.get_stock_history_controller import GetStockHistoryController
from myproject.core.infrastructure.controllers.stock.get_stocks_overview_controller import GetStocksOverviewController
from myproject.core.infrastructure.controllers.stock.post_stock_snapshot_controller import PostStockSnapshotController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),
    path('stocks/', GetStockController.as_view(), name='get_stock'),
    path('stocks/<str:text>', GetStockBySymbolController.as_view(), name='get_stock_by_symbol'),
    path('stock/snapshot/save', PostStockSnapshotController.as_view(), name='post_stock_snapshot'),
    path('portfolio/<int:id_portafolio>/results', GetResultsPortafolioController.as_view(), name='get_results_portafolio'),
    path('stocks/<str:symbol>/history', GetStockHistoryController.as_view(), name='get_stock_history'),
    path('stocks/current/state', GetCurrentStocksController.as_view(), name='get_current_stocks'),
    path('stocks/overview/list', GetStocksOverviewController.as_view(), name='get_stocks_overview'),
    path("analysis/daily", GetDailyAnalysisController.as_view(), name="analysis_daily"),
]
