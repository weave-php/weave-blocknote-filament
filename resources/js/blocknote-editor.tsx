import React, { useEffect, useMemo } from 'react';
import { createRoot, type Root } from 'react-dom/client';
import { useCreateBlockNote } from '@blocknote/react';
import { BlockNoteView } from '@blocknote/mantine';
import type { BlockNoteEditor as BlockNoteEditorInstance } from '@blocknote/core';
import '@blocknote/mantine/style.css';
import '../css/blocknote-shell.css';

export function BlockNoteEditor({
    minHeight,
    getState,
    setState,
    onReady,
}: {
    minHeight?: string;
    getState: () => string | null | undefined;
    setState: (json: string) => void;
    onReady?: (editor: BlockNoteEditorInstance) => void;
}) {
    const options = useMemo(() => {
        try {
            const raw = getState();
            if (!raw) {
                return {};
            }
            const parsed = JSON.parse(raw) as unknown;
            return Array.isArray(parsed) ? { initialContent: parsed } : {};
        } catch {
            return {};
        }
    }, []);

    const editor = useCreateBlockNote(options);

    useEffect(() => {
        onReady?.(editor);

        if (!getState()) {
            setState(JSON.stringify(editor.document ?? []));
        }
    }, [editor, getState, onReady, setState]);

    const shellStyle: React.CSSProperties = {
        display: 'flex',
        flexDirection: 'column',
        width: '100%',
        boxSizing: 'border-box',
    };
    if (minHeight) {
        shellStyle.minHeight = minHeight;
    }

    return (
        <div className="weave-blocknote-shell" style={shellStyle}>
            <BlockNoteView
                editor={editor}
                onChange={() => setState(JSON.stringify(editor.document ?? []))}
            />
        </div>
    );
}

window.WeaveBlockNote = {
    mount(root: HTMLElement | null, props: Parameters<typeof BlockNoteEditor>[0]) {
        if (!root || root.dataset.blocknoteMounted === '1') {
            return;
        }

        const reactRoot = createRoot(root);
        reactRoot.render(<BlockNoteEditor {...props} />);

        root.dataset.blocknoteMounted = '1';
        (root as HTMLElement & { __weaveBlockNoteRoot?: Root }).__weaveBlockNoteRoot = reactRoot;
    },
};

declare global {
    interface Window {
        WeaveBlockNote?: {
            mount: (root: HTMLElement | null, props: Parameters<typeof BlockNoteEditor>[0]) => void;
        };
    }
}
