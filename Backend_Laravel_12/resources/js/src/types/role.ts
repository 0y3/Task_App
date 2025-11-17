import { Role } from './user';

// export interface Role {
//   id: number;
//   name: string;
//   slug: string;
//   permissions?: number[]; // optional: permission IDs
//   created_at?: string;
//   updated_at?: string;
// }

export interface RolePayload {
  name: string;
  slug: string;
  permissions: number[]; // permission IDs
}

export interface RoleResponse {
  status: boolean;
  message: string;
  data: Role;
}

export interface RolesListResponse {
  status: boolean;
  message: string;
  data: Role[];
}
