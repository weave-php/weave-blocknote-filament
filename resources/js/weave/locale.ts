import * as blockNoteLocales from '@blocknote/core/locales';

export function resolveBlockNoteDictionary(locale: string): (typeof blockNoteLocales)['en'] {
    const normalized = locale.replace(/_/g, '-').toLowerCase();
    if (normalized === 'zh-tw') {
        return blockNoteLocales.zhTW;
    }
    const primary = normalized.split('-')[0];
    if (primary in blockNoteLocales) {
        return blockNoteLocales[primary as keyof typeof blockNoteLocales];
    }
    if (locale in blockNoteLocales) {
        return blockNoteLocales[locale as keyof typeof blockNoteLocales];
    }
    return blockNoteLocales.en;
}
