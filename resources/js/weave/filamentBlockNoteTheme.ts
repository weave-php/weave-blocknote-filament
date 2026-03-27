import {
    darkDefaultTheme,
    lightDefaultTheme,
    type Theme,
} from '@blocknote/mantine';

const filamentLightTheme = {
    ...lightDefaultTheme,
    colors: {
        ...lightDefaultTheme.colors,
        editor: {
            text: 'var(--gray-950)',
            background: 'var(--color-white)',
        },
        menu: {
            text: 'var(--gray-950)',
            background: 'var(--color-white)',
        },
        tooltip: {
            text: 'var(--gray-950)',
            background: 'var(--gray-100)',
        },
        hovered: {
            text: 'var(--gray-950)',
            background: 'var(--gray-100)',
        },
        selected: {
            text: 'var(--color-white)',
            background: 'var(--gray-700)',
        },
        disabled: {
            text: 'var(--gray-400)',
            background: 'var(--gray-100)',
        },
        shadow: 'var(--gray-300)',
        border: 'var(--gray-200)',
        sideMenu: 'var(--gray-400)',
        highlights: lightDefaultTheme.colors!.highlights,
    },
} satisfies Theme;

const filamentDarkTheme = {
    ...darkDefaultTheme,
    colors: {
        ...darkDefaultTheme.colors,
        editor: {
            text: 'var(--gray-50)',
            background: 'var(--gray-900)',
        },
        menu: {
            text: 'var(--gray-50)',
            background: 'var(--gray-900)',
        },
        tooltip: {
            text: 'var(--gray-50)',
            background: 'var(--gray-800)',
        },
        hovered: {
            text: 'var(--gray-50)',
            background: 'var(--gray-800)',
        },
        selected: {
            text: 'var(--gray-50)',
            background: 'var(--gray-700)',
        },
        disabled: {
            text: 'var(--gray-500)',
            background: 'var(--gray-800)',
        },
        shadow: 'var(--gray-950)',
        border: 'var(--gray-700)',
        sideMenu: 'var(--gray-500)',
        highlights: darkDefaultTheme.colors!.highlights,
    },
} satisfies Theme;

export const filamentBlockNoteTheme = {
    light: filamentLightTheme,
    dark: filamentDarkTheme,
};
