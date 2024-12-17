FROM python:3.10
WORKDIR /app
COPY project/backend/django/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY project/backend/django/ .
EXPOSE 8000
CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
