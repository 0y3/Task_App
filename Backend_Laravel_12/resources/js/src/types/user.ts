export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  role: Role | null;
  created_at?: string;
  updated_at?: string;
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

export interface UserResponse {
  id: number;
  name: string;
  email: string;
  roles?: string[];
  permissions?: Record<string, string[]>;
}


export interface UserListResponse {
  status: boolean|number;
  message: string;
  data?: any;
  errors?: Record<string, string[]>;
}

export interface UserRolePermission {
  id: number;
  name: string;
  email: string;
  roles?: string[];
  permissions?: Record<string, string[]>;
}

export interface UserRolePermissionResponse extends Record<string, any> {
  data: {
    data: Array<UserRolePermission>;
  };
}
