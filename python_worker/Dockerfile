FROM python:3.10-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

# Keeps the container alive so we can exec into it to run ingestion manually
CMD ["tail", "-f", "/dev/null"]
