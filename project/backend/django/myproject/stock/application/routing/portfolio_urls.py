from django.urls import path
from myproject.stock.infrastructure.controllers.create_portfolio_controller import CreatePortfolioController
from myproject.stock.infrastructure.controllers.get_portfolios_list_controller import GetPortfoliosListController


urlpatterns = [
    path('create', CreatePortfolioController.as_view(), name='create_portfolio'),
    path('list', GetPortfoliosListController.as_view(), name='get_portfolios_list'),
]
