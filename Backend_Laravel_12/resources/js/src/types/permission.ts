
import { Permission } from './user';

export interface PermissionPayload {
  name: string;
  slug: string;
}

export interface PermissionResponse {
  status: boolean;
  message: string;
  data: Permission;
}

export interface PermissionsListResponse {
  status: boolean;
  message: string;
  data: Permission[];
}
