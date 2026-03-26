import React from 'react';
import { createRoot, type Root } from 'react-dom/client';
import { BlockNoteField } from './weave/BlockNoteField';
import type { BlockNoteFieldProps } from './weave/types';

window.WeaveBlockNote = {
    mount(root: HTMLElement | null, props: BlockNoteFieldProps) {
        if (!root || root.dataset.blocknoteMounted === '1') {
            return;
        }

        const reactRoot = createRoot(root);
        reactRoot.render(<BlockNoteField {...props} />);

        root.dataset.blocknoteMounted = '1';
        (root as HTMLElement & { __weaveBlockNoteRoot?: Root }).__weaveBlockNoteRoot = reactRoot;
    },

    unmount(root: HTMLElement | null) {
        if (!root) {
            return;
        }

        const el = root as HTMLElement & { __weaveBlockNoteRoot?: Root };
        if (el.__weaveBlockNoteRoot) {
            el.__weaveBlockNoteRoot.unmount();
            delete el.__weaveBlockNoteRoot;
        }

        delete root.dataset.blocknoteMounted;
    },
};

declare global {
    interface Window {
        WeaveBlockNote?: {
            mount: (root: HTMLElement | null, props: BlockNoteFieldProps) => void;
            unmount: (root: HTMLElement | null) => void;
        };
    }
}
