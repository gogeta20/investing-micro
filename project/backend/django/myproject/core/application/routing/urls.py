from django.urls import path
from myproject.core.infrastructure.controllers.health_check_controller import HealthCheckController

urlpatterns = [
    path('', HealthCheckController.as_view(), name='health_check'),
]
