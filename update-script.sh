#!/bin/sh

echo "$(date): Starting OSM data update..."

# Check if Nominatim is ready
while ! curl -s http://nominatim:8080/status | grep -q "ready"; do
    echo "Waiting for Nominatim to be ready..."
    sleep 30
done

# Run the update
echo "Running Nominatim update..."
if ! docker exec nominatim nominatim replication --project-dir /nominatim; then
    echo "$(date): Update failed during replication"
    exit 1
fi

# Check if update was successful
if [ $? -eq 0 ]; then
    echo "$(date): Update completed successfully"
    
    # Clear Redis cache after update
    echo "Clearing Redis cache..."
    if ! docker exec nominatim-redis redis-cli FLUSHALL; then
        echo "$(date): Failed to clear Redis cache"
        exit 1
    fi
    
    # Restart Nominatim to apply changes
    echo "Restarting Nominatim..."
    if ! docker restart nominatim; then
        echo "$(date): Failed to restart Nominatim"
        exit 1
    fi
else
    echo "$(date): Update failed"
    exit 1
fi 