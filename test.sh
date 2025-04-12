#!/bin/bash

echo "Testing Nominatim setup..."

# Check if Nominatim container is running
if ! docker-compose ps | grep -q "nominatim.*Up"; then
    echo "Error: Nominatim container is not running"
    exit 1
fi

echo "1. Testing Nominatim status..."
curl -s http://localhost:8080/status | jq

echo -e "\n2. Testing search functionality..."
echo "Searching for 'Manila'..."
curl -s "http://localhost:8080/search?q=Manila&format=json&countrycodes=ph&limit=1" | jq

echo -e "\n3. Testing reverse geocoding..."
echo "Reverse geocoding for Manila coordinates..."
curl -s "http://localhost:8080/reverse?lat=14.5995&lon=120.9842&format=json" | jq

echo -e "\n4. Testing API server..."
echo "Testing API search endpoint..."
curl -s "http://localhost:3000/search?query=Manila" | jq

echo -e "\nTesting API reverse geocoding endpoint..."
curl -s "http://localhost:3000/reverse?lat=14.5995&lon=120.9842" | jq

echo -e "\nTest completed!" 