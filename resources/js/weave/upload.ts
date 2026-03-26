function getByPath(obj: unknown, path: string): unknown {
    return path.split('.').reduce<unknown>((acc, part) => {
        if (acc !== null && typeof acc === 'object' && part in (acc as object)) {
            return (acc as Record<string, unknown>)[part];
        }
        return undefined;
    }, obj);
}

export function createLaravelUploadHandler(options: {
    url: string;
    fieldName: string;
    responseUrlKey: string;
}): (file: File, blockId?: string) => Promise<string | Record<string, unknown>> {
    const { url, fieldName, responseUrlKey } = options;
    return async (file: File) => {
        const body = new FormData();
        body.append(fieldName, file);
        const headers: Record<string, string> = { Accept: 'application/json' };
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) {
            headers['X-CSRF-TOKEN'] = csrf;
        }
        const xsrfPair = document.cookie.split('; ').find((c) => c.startsWith('XSRF-TOKEN='));
        if (xsrfPair) {
            headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfPair.split('=')[1] ?? '');
        }
        const res = await fetch(url, {
            method: 'POST',
            body,
            headers,
            credentials: 'same-origin',
        });
        if (!res.ok) {
            const text = await res.text().catch(() => '');
            throw new Error(text || `Upload failed (${res.status})`);
        }
        const data: unknown = await res.json();
        const fileUrl = getByPath(data, responseUrlKey);
        if (typeof fileUrl !== 'string' || fileUrl === '') {
            throw new Error('Upload response has no file URL at key: ' + responseUrlKey);
        }
        return fileUrl;
    };
}
