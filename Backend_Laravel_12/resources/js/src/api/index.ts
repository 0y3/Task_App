import axios, {AxiosResponse,AxiosError} from 'axios';
import { showErrorToast } from '@/utils/toast-notification';

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8089/api',
    timeout: parseInt(import.meta.env.VITE_API_TIMEOUT || '5000'),
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

/**
 * Inject Authorization token automatically
 */
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

/**
 * Normalize API responses to always return: response.data
 */
// apiClient.interceptors.response.use(
//     (response: AxiosResponse) => response.data,
//     (error: AxiosError) => {
//         // Optional: global error handling hook
//         const errorMessage = (error.response?.data as any)?.message ?? "Something went wrong.";
        
//         // console.error(
//         // "API Error:",
//         // error.response?.status,
//         // error.response?.data || error.message
//         // );


//         // showErrorToast(errorMessage);
//         // Re-throw for local error handling
//         return Promise.reject(error);
//     }
// );

export default apiClient;
