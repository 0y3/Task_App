import { User } from './user';

export interface LoginPayload {
  email: string;
  password: string;
}

export interface RegisterPayload {
  name: string;
  email: string;
  password: string;
  password_confirmation?: string;
}

export interface AuthResponse {
  status: boolean;
  message: string;
  token?: string;
  user: User;
}

export interface AuthState {
  user: User | null;
  token: string | null;
}
