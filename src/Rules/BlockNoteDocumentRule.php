<?php

namespace Weave\BlockNote\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that the value is a JSON string of a BlockNote document: a non-empty array of blocks with a "type" key each.
 */
final class BlockNoteDocumentRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $fail('The :attribute must be valid JSON.');

            return;
        }

        if (! is_array($decoded)) {
            $fail('The :attribute must be a JSON array of blocks.');

            return;
        }

        if ($decoded === []) {
            $fail('The :attribute must contain at least one block.');

            return;
        }

        foreach ($decoded as $block) {
            if (! is_array($block)) {
                $fail('Each block in :attribute must be an object.');

                return;
            }
            if (! isset($block['type']) || ! is_string($block['type']) || $block['type'] === '') {
                $fail('Each block in :attribute must have a non-empty type.');

                return;
            }
        }
    }
}
