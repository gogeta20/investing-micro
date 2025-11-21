from django.urls import path, include
from myproject.core.infrastructure.controllers.health.health_check_controller import HealthCheckController

urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),
]
