<?php

namespace Weave\BlockNote\Support;

final class BlockNoteDocument
{
    public static function toPlainText(?string $json, int $limit = 500): string
    {
        if ($json === null || $json === '') {
            return '';
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            return '';
        }

        $parts = [];
        self::collectText($decoded, $parts);
        $text = trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)) ?? '');

        if ($limit <= 0 || mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit).'…';
    }

    private static function collectText(array $blocks, array &$parts): void
    {
        foreach ($blocks as $block) {
            if (! is_array($block)) {
                continue;
            }

            if (isset($block['content']) && is_array($block['content'])) {
                foreach ($block['content'] as $item) {
                    if (is_array($item) && ($item['type'] ?? '') === 'text' && isset($item['text'])) {
                        $parts[] = (string) $item['text'];
                    }
                }
            }

            if (isset($block['children']) && is_array($block['children'])) {
                self::collectText($block['children'], $parts);
            }
        }
    }
}
