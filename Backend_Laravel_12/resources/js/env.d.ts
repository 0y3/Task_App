declare global {
    // Makes these types available everywhere (no import needed)

    // Type declarations for Vite's import.meta.env — add VITE_ variables here
    interface ImportMetaEnv {
        readonly VITE_APP_URL?: string;
        readonly VITE_API_URL?: string;
        readonly VITE_FEATURE_FLAG?: 'true' | 'false';
        readonly VITE_API_TIMEOUT?: string; // parse as number if needed
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
    }

}
export {};
