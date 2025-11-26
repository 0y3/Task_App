import apiClient from "./index";
import type { AuthResponse, LoginPayload,RegisterPayload } from "@/types/auth";

export const login2 = (credentials: LoginPayload) => {
    return apiClient.post('/auth/login', credentials);
};

export async function login(payload: LoginPayload): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>("/auth/login", payload);
    // Optionally store token
    if(response.data.token) {
        localStorage.setItem("token", response.data.token);
    }
    // console.log(response);
    return response.data;
}

export async function register(payload: RegisterPayload): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>("/auth/register", payload);
    return response.data;
}

export async function logout(): Promise<void> {
    await apiClient.post("/auth/logout");
    localStorage.removeItem("token");
}

export async function getUser(): Promise<AuthResponse> {
    const response = await apiClient.get<AuthResponse>("/auth/user");
    return response.data;
}