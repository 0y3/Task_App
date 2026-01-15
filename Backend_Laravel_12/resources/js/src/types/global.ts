
export interface DataTableResponse {
  draw: number;
  recordsTotal: number;
  recordsFiltered: number;
  data: any[];
}

export interface DataTablePayload {
    draw: number;
    start?: number;
    length: number;
    search:{ value:string; };
    status?: string | number | null;
    order: Array<{
        column: number | string;
        dir: 'asc' | 'desc';
    }>;
    // colums:Record<string, string[]>;
    columns: Array<{
        data: string;
        // name: string;
        // searchable: boolean;
        // orderable: boolean;
        // search: {
        //     value: string;
        //     regex: boolean;
        // };
    }>;
}
