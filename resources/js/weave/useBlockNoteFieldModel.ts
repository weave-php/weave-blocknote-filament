import { useEffect } from 'react';
import type { BlockNoteEditor } from '@blocknote/core';
import { parseWireBlocks } from './wire-state';
import type { BlockNoteFieldProps } from './types';

/**
 * Syncs Livewire JSON with the editor: persists BlockNote’s default when empty, hydrates saved blocks after mount.
 * Never uses initialContent in BlockNote.create (BlockNote treats [] as truthy and skips its default document).
 */
export function useBlockNoteFieldModel(
    editor: BlockNoteEditor,
    { getState, setState, onReady }: Pick<BlockNoteFieldProps, 'getState' | 'setState' | 'onReady'>,
) {
    useEffect(() => {
        onReady?.(editor);
    }, [editor, onReady]);

    useEffect(() => {
        const raw = getState();
        const blocks = parseWireBlocks(typeof raw === 'string' ? raw : undefined);
        if (!blocks?.length) {
            setState(JSON.stringify(editor.document ?? []));
        }
    }, [editor, getState, setState]);

    useEffect(() => {
        const tryHydrate = () => {
            const raw = getState();
            if (raw === undefined || raw === null || String(raw).trim() === '') {
                return;
            }
            const blocks = parseWireBlocks(typeof raw === 'string' ? raw : undefined);
            if (!blocks?.length) {
                return;
            }
            let docJson: string;
            try {
                docJson = JSON.stringify(editor.document ?? []);
            } catch {
                return;
            }
            if (docJson === String(raw).trim()) {
                return;
            }
            try {
                editor.replaceBlocks(editor.document, blocks as Parameters<typeof editor.replaceBlocks>[1]);
            } catch (e) {
                console.warn('BlockNote: could not restore document', e);
            }
        };
        const t0 = window.setTimeout(tryHydrate, 0);
        const t1 = window.setTimeout(tryHydrate, 100);
        return () => {
            window.clearTimeout(t0);
            window.clearTimeout(t1);
        };
    }, [editor, getState]);
}
