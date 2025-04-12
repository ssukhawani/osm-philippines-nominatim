#!/bin/bash

# Create necessary directories
mkdir -p data

# Pull the latest Nominatim image
docker-compose pull

# Start the services
docker-compose up -d

echo "Waiting for Nominatim to initialize (this may take a while)..."
echo "You can check the progress with: docker-compose logs -f nominatim"

# The initial import might take several hours depending on your system
# You can monitor the progress in the logs 