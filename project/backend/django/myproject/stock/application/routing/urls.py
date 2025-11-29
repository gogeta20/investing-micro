from django.urls import path
from myproject.stock.infrastructure.controllers.get_current_stocks_controller import GetCurrentStocksController
from myproject.stock.infrastructure.controllers.get_daily_analysis_controller import GetDailyAnalysisController
from myproject.stock.infrastructure.controllers.get_results_portafolio_controller import GetResultsPortafolioController
from myproject.stock.infrastructure.controllers.get_stock_by_symbol_controller import GetStockBySymbolController
from myproject.stock.infrastructure.controllers.get_stock_controller import GetStockController
from myproject.stock.infrastructure.controllers.get_stock_history_controller import GetStockHistoryController
from myproject.stock.infrastructure.controllers.get_stock_history_period_controller import GetStockHistoryPeriodController
from myproject.stock.infrastructure.controllers.get_stocks_overview_controller import GetStocksOverviewController
from myproject.stock.infrastructure.controllers.post_stock_snapshot_controller import PostStockSnapshotController
from myproject.stock.infrastructure.controllers.get_stock_valuation_controller import GetStockValuationController
from myproject.stock.infrastructure.controllers.search_stock_by_symbol_controller import SearchStockBySymbolController
from myproject.stock.infrastructure.controllers.create_stock_controller import CreateStockController


urlpatterns = [
    # Rutas específicas primero (deben ir antes de las genéricas)
    path('', GetStockController.as_view(), name='get_stock'),
    path('create', CreateStockController.as_view(), name='create_stock'),
    path('search/<str:symbol>', SearchStockBySymbolController.as_view(), name='search_stock_by_symbol'),
    path('snapshot/save', PostStockSnapshotController.as_view(), name='post_stock_snapshot'),
    path('current/state', GetCurrentStocksController.as_view(), name='get_current_stocks'),
    path('overview/list', GetStocksOverviewController.as_view(), name='get_stocks_overview'),
    path('portfolio/<int:id_portafolio>/results', GetResultsPortafolioController.as_view(), name='get_results_portafolio'),
    path('analysis/daily', GetDailyAnalysisController.as_view(), name="analysis_daily"),
    # Rutas con parámetros dinámicos
    path('<str:symbol>/history', GetStockHistoryPeriodController.as_view(), name='get_stock_history_period'),
    path('<str:symbol>/history/old', GetStockHistoryController.as_view(), name='get_stock_history'),
    path('<str:symbol>/valuation', GetStockValuationController.as_view(), name='get_stock_valuation'),
    # Ruta genérica al final (captura cualquier string que no coincida con las anteriores)
    path('<str:text>', GetStockBySymbolController.as_view(), name='get_stock_by_symbol'),
]
