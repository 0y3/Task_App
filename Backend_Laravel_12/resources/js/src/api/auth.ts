import apiClient from "./index";

export const login = (credentials:{ email: string; password: string }) => {
    return apiClient.post('/auth/login', credentials);
};

export const register = (data:{ name: string; email: string; password: string; password_confirmation: string }) => {
    return apiClient.post('/auth/register', data);
};

export const logout = () => {
    return apiClient.post('/auth/logout');
};

export const getUser = () => {
    return apiClient.get('/auth/user');
};
