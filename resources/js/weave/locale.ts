import * as L from '@blocknote/core/locales';

const byPrimaryLanguage: Record<string, typeof L.en> = {
    ar: L.ar,
    de: L.de,
    en: L.en,
    es: L.es,
    fa: L.fa,
    fr: L.fr,
    he: L.he,
    hr: L.hr,
    is: L.is,
    it: L.it,
    ja: L.ja,
    ko: L.ko,
    nl: L.nl,
    no: L.no,
    pl: L.pl,
    pt: L.pt,
    ru: L.ru,
    sk: L.sk,
    uk: L.uk,
    uz: L.uz,
    vi: L.vi,
    zh: L.zh,
};

export function resolveBlockNoteDictionary(locale: string): typeof L.en {
    const normalized = locale.replace(/_/g, '-').toLowerCase();
    if (normalized === 'zh-tw' || normalized === 'zh-hk' || normalized === 'zh-mo') {
        return L.zhTW;
    }
    const primary = normalized.split('-')[0] ?? 'en';

    return byPrimaryLanguage[primary] ?? L.en;
}
