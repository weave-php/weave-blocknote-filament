import type { BlockNoteEditor } from '@blocknote/core';

export type BlockNoteFieldProps = {
    minHeight?: string;
    locale?: string;
    uploadUrl?: string;
    uploadFieldName?: string;
    uploadResponseUrlKey?: string;
    blockKeys?: string[];
    getState: () => string | null | undefined;
    setState: (json: string) => void;
    onReady?: (editor: BlockNoteEditor) => void;
};
