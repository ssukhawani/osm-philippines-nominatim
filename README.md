# Nominatim POC for Coffee Pickup App

This is a Proof of Concept (POC) for using a local Nominatim instance as an alternative to Google Maps API for geocoding and reverse geocoding in the Philippines region.

## Features

- Local Nominatim instance running in Docker
- Geocoding (address to coordinates)
- Reverse Geocoding (coordinates to address)
- Focused on Philippines region
- Simple REST API interface

## Prerequisites

- Docker and Docker Compose installed
- At least 50GB of free disk space (for Philippines data)
- At least 8GB of RAM recommended

## Setup

1. Make the setup script executable:
```bash
chmod +x setup.sh
```

2. Run the setup script:
```bash
./setup.sh
```

This will:
- Pull the Nominatim Docker image
- Start the container
- Begin importing Philippines OSM data

Note: The initial import might take several hours depending on your system. You can monitor the progress with:
```bash
docker-compose logs -f nominatim
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Start the API server:
```bash
npm start
```

## API Endpoints

### Search (Geocoding)
```
GET /search?query=<address>
```
Example:
```
GET /search?query=Manila
```

### Reverse Geocoding
```
GET /reverse?lat=<latitude>&lon=<longitude>
```
Example:
```
GET /reverse?lat=14.5995&lon=120.9842
```

## Important Notes

1. The initial data import is a one-time process that might take several hours
2. The container uses a volume to persist data between restarts
3. Updates to the OSM data can be configured through the REPLICATION_URL
4. The container exposes Nominatim on port 8080

## Testing

To test the endpoints, you can use curl or any HTTP client:

```bash
# Search example
curl "http://localhost:3000/search?query=Manila"

# Reverse geocoding example
curl "http://localhost:3000/reverse?lat=14.5995&lon=120.9842"
```

## Maintenance

To update the OSM data:
```bash
docker-compose exec nominatim nominatim replication --project-dir /nominatim
```

To restart the services:
```bash
docker-compose restart
```

To view logs:
```bash
docker-compose logs -f nominatim
``` 