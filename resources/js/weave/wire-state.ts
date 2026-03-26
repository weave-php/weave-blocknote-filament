export function parseWireBlocks(raw: string | null | undefined): unknown[] | undefined {
    if (raw === undefined || raw === null || String(raw).trim() === '') {
        return undefined;
    }
    let parsed: unknown;
    try {
        parsed = JSON.parse(String(raw).trim());
    } catch {
        return undefined;
    }
    if (!Array.isArray(parsed) || parsed.length === 0) {
        return undefined;
    }
    const blocks = parsed.filter(
        (b) => b !== null && typeof b === 'object' && typeof (b as { type?: unknown }).type === 'string',
    );
    if (blocks.length === 0) {
        return undefined;
    }
    return blocks;
}
