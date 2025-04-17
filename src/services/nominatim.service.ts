import axios from 'axios';
import Redis from 'ioredis';

export interface NominatimResponse {
    place_id: number;
    licence: string;
    osm_type: string;
    osm_id: number;
    lat: string;
    lon: string;
    display_name: string;
    address: {
        [key: string]: string;
    };
}

export class NominatimService {
    private readonly baseUrl: string;
    private readonly userAgent: string;
    private readonly redis: Redis;
    private readonly CACHE_TTL = 24 * 60 * 60; // 24 hours in seconds

    constructor() {
        this.baseUrl = 'http://localhost:8080';
        this.userAgent = 'CoffeePickupApp/1.0';
        this.redis = new Redis({
            host: 'localhost',
            port: 6379,
            retryStrategy: (times) => {
                const delay = Math.min(times * 50, 2000);
                return delay;
            }
        });
    }

    private async getFromCache<T>(key: string): Promise<T | null> {
        try {
            const cached = await this.redis.get(key);
            return cached ? JSON.parse(cached) : null;
        } catch (error) {
            console.error('Redis cache error:', error);
            return null;
        }
    }

    private async setInCache(key: string, value: any): Promise<void> {
        try {
            await this.redis.setex(key, this.CACHE_TTL, JSON.stringify(value));
        } catch (error) {
            console.error('Redis cache error:', error);
        }
    }

    async search(query: string, country: string = 'Philippines'): Promise<NominatimResponse[]> {
        const cacheKey = `search:${query}:${country}`;
        
        // Try to get from cache first
        const cached = await this.getFromCache<NominatimResponse[]>(cacheKey);
        if (cached) {
            console.log('Cache hit for search:', query);
            return cached;
        }

        try {
            const response = await axios.get(`${this.baseUrl}/search`, {
                params: {
                    q: `${query}, ${country}`,
                    format: 'json',
                    limit: 5,
                    countrycodes: 'ph',
                    addressdetails: 1,
                    'accept-language': 'en'
                },
                headers: {
                    'User-Agent': this.userAgent
                }
            });

            // Cache the results
            await this.setInCache(cacheKey, response.data);
            
            return response.data;
        } catch (error) {
            console.error('Error in Nominatim search:', error);
            throw error;
        }
    }

    async reverseGeocode(lat: number, lon: number): Promise<NominatimResponse> {
        const cacheKey = `reverse:${lat}:${lon}`;
        
        // Try to get from cache first
        const cached = await this.getFromCache<NominatimResponse>(cacheKey);
        if (cached) {
            console.log('Cache hit for reverse geocode:', lat, lon);
            return cached;
        }

        try {
            const response = await axios.get(`${this.baseUrl}/reverse`, {
                params: {
                    lat,
                    lon,
                    format: 'json',
                    addressdetails: 1,
                    'accept-language': 'en'
                },
                headers: {
                    'User-Agent': this.userAgent
                }
            });

            // Cache the results
            await this.setInCache(cacheKey, response.data);
            
            return response.data;
        } catch (error) {
            console.error('Error in Nominatim reverse geocoding:', error);
            throw error;
        }
    }
} 