export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  role: Role | null;
  created_at: string;
  updated_at: string;
}

export interface Permission {
  id: number;
  name: string;
  slug: string;
}

export interface Role {
  id: number;
  name: string;
  slug: string;
  permissions: Permission[];
}
