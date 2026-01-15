import apiClient from "./index";
import type { DataTablePayload, DataTableResponse } from "@/types/global";

export async function getUsers(payload: DataTablePayload): Promise<DataTableResponse> {
    // const response = await apiClient.post<DataTableResponse>("/users/roleuserDT", payload);
    const response = await apiClient.get<DataTableResponse>("/users/roleuserDT", {params: payload});
    return response.data;
}

// export async function getUser(): Promise<AuthResponse> {
//     const response = await apiClient.get<AuthResponse>("/auth/user");
//     return response.data;
// }