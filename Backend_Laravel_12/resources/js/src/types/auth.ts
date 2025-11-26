import { User } from './user';

export interface LoginPayload {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterPayload {
  name: string;
  email: string;
  password: string;
  password_confirmation?: string;
}

export interface AuthResponse {
  status: boolean|number;
  message: string;
  token?: string;
  user: User|null;
  data?: any;
  errors?: Record<string, string[]>;
}

export interface AuthState {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
}
