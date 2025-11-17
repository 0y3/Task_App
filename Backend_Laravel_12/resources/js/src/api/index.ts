import axios from 'axios';

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost/api',
    timeout: parseInt(import.meta.env.VITE_API_TIMEOUT || '5000'),
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

export default apiClient;
