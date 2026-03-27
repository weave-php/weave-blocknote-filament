import React, { useMemo } from 'react';
import { BlockNoteContext, useCreateBlockNote } from '@blocknote/react';
import { BlockNoteView } from '@blocknote/mantine';
import type { BlockNoteFieldProps } from './types';
import { filamentBlockNoteTheme } from './filamentBlockNoteTheme';
import { resolveBlockNoteDictionary } from './locale';
import { createSchemaFromBlockKeys, parseBlockKeysJson } from './schema';
import { createLaravelUploadHandler } from './upload';
import { useBlockNoteFieldModel } from './useBlockNoteFieldModel';
import { useFilamentBlockNoteColorScheme } from './useFilamentBlockNoteColorScheme';
import '@blocknote/mantine/style.css';
import '../../css/blocknote-shell.css';

export function BlockNoteField({
    minHeight,
    locale,
    uploadUrl,
    uploadFieldName,
    uploadResponseUrlKey,
    blockKeys,
    getState,
    setState,
    onReady,
}: BlockNoteFieldProps) {
    const fieldName = uploadFieldName ?? 'file';
    const responseKey = uploadResponseUrlKey ?? 'url';
    const blocksDep = blockKeys ? JSON.stringify(blockKeys) : '';

    const editorOptions = useMemo(() => {
        const keys = parseBlockKeysJson(blocksDep);
        const base: Parameters<typeof useCreateBlockNote>[0] = {
            schema: createSchemaFromBlockKeys(keys),
        };
        if (locale) {
            base.dictionary = resolveBlockNoteDictionary(locale);
        }
        if (uploadUrl) {
            base.uploadFile = createLaravelUploadHandler({
                url: uploadUrl,
                fieldName,
                responseUrlKey: responseKey,
            });
        }
        return base;
    }, [locale, uploadUrl, fieldName, responseKey, blocksDep]);

    const editor = useCreateBlockNote(editorOptions, [
        locale,
        uploadUrl,
        fieldName,
        responseKey,
        blocksDep,
    ]);

    useBlockNoteFieldModel(editor, { getState, setState, onReady });

    const colorSchemePreference = useFilamentBlockNoteColorScheme();

    const shellStyle: React.CSSProperties = {
        display: 'flex',
        flexDirection: 'column',
        width: '100%',
        boxSizing: 'border-box',
        ...(minHeight ? { minHeight } : {}),
    };

    const blockNoteContextValue = useMemo(
        () => ({ colorSchemePreference }),
        [colorSchemePreference],
    );

    return (
        <BlockNoteContext.Provider value={blockNoteContextValue}>
            <div className="weave-blocknote-shell" style={shellStyle}>
                <BlockNoteView
                    editor={editor}
                    theme={filamentBlockNoteTheme}
                    onChange={() =>
                        setState(JSON.stringify(editor.document ?? []))
                    }
                />
            </div>
        </BlockNoteContext.Provider>
    );
}
