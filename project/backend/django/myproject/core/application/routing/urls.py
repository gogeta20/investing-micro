from django.urls import path
from myproject.core.infrastructure.controllers.health_check_controller import HealthCheckController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),  # Ruta específica del health check
]
