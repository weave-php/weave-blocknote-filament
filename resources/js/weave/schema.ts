import { BlockNoteSchema, defaultBlockSpecs } from '@blocknote/core';

const ALL_KEYS = Object.keys(defaultBlockSpecs) as (keyof typeof defaultBlockSpecs)[];

export function createSchemaFromBlockKeys(blockKeys: string[]) {
    const set = new Set(blockKeys);
    const isFull =
        set.size === ALL_KEYS.length && ALL_KEYS.every((k) => set.has(k as string));
    if (isFull) {
        return BlockNoteSchema.create();
    }
    const picked: Record<string, (typeof defaultBlockSpecs)[keyof typeof defaultBlockSpecs]> = {};
    for (const key of blockKeys) {
        if (key in defaultBlockSpecs) {
            picked[key] = defaultBlockSpecs[key as keyof typeof defaultBlockSpecs];
        }
    }
    if (!('paragraph' in picked)) {
        picked.paragraph = defaultBlockSpecs.paragraph;
    }
    return BlockNoteSchema.create({
        blockSpecs: picked as typeof defaultBlockSpecs,
    });
}

export function parseBlockKeysJson(blocksDep: string): string[] {
    if (blocksDep === '') {
        return [...ALL_KEYS];
    }
    try {
        return JSON.parse(blocksDep) as string[];
    } catch {
        return [...ALL_KEYS];
    }
}
