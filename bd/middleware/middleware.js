const express = require('express');
const axios = require('axios');
const cors = require('cors');

const app = express();
app.use(express.json());

app.use(cors());

// Middleware para verificar disponibilidad de los microservicios
app.use(async (req, res, next) => {
    try {
        // Verificar disponibilidad del microservicio adicional
        await axios.get('http://localhost:8001/status'); // Ejemplo de verificación de estado
        next();
    } catch (error) {
        // Redirigir a servicio espejo si el servicio principal falla
        req.headers['Service-Redirect'] = 'espejo';
        next();
    }
});

// Rutas para manejar las solicitudes
app.post('/api/register', async (req, res) => {
    try {
        const response = await axios.post('http://localhost:8000/api/register', req.body);
        res.json(response.data);
    } catch (error) {
        res.status(500).json({ message: 'Error en el registro' });
    }
});

app.post('/api/login', async (req, res) => {
    try {
        const response = await axios.post('http://localhost:8000/api/login', req.body);
        res.json(response.data);
    } catch (error) {
        res.status(500).json({ message: 'Error en el inicio de sesión' });
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Middleware corriendo en http://localhost:${PORT}`);
});