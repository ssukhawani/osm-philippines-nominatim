import express from 'express';
import cors from 'cors';
import { NominatimService } from './services/nominatim.service';

const app = express();
const port = process.env.PORT || 3000;
const nominatimService = new NominatimService();

app.use(cors());
app.use(express.json());

// Search endpoint
app.get('/search', async (req, res) => {
    try {
        const { query } = req.query;
        if (!query) {
            return res.status(400).json({ error: 'Query parameter is required' });
        }
        const results = await nominatimService.search(query as string);
        res.json(results);
    } catch (error) {
        res.status(500).json({ error: 'Internal server error' });
    }
});

// Reverse geocoding endpoint
app.get('/reverse', async (req, res) => {
    try {
        const { lat, lon } = req.query;
        if (!lat || !lon) {
            return res.status(400).json({ error: 'Latitude and longitude are required' });
        }
        const result = await nominatimService.reverseGeocode(
            parseFloat(lat as string),
            parseFloat(lon as string)
        );
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: 'Internal server error' });
    }
});

app.listen(port, () => {
    console.log(`Server running on port ${port}`);
}); 