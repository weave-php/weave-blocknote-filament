import { useSyncExternalStore } from 'react';

function isDocumentDark(): boolean {
    return document.documentElement.classList.contains('dark');
}

function subscribeToHtmlClass(callback: () => void): () => void {
    const observer = new MutationObserver(callback);
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
    return () => observer.disconnect();
}

export function useFilamentBlockNoteColorScheme(): 'light' | 'dark' {
    return useSyncExternalStore(
        subscribeToHtmlClass,
        () => (isDocumentDark() ? 'dark' : 'light'),
        () => 'light',
    );
}
