<?php

namespace Weave\BlockNote\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class BlockNoteDocumentRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail(__('weave-blocknote::validation.blocknote_document.must_be_string', ['attribute' => $attribute]));

            return;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $fail(__('weave-blocknote::validation.blocknote_document.must_be_valid_json', ['attribute' => $attribute]));

            return;
        }

        if (! is_array($decoded)) {
            $fail(__('weave-blocknote::validation.blocknote_document.must_be_array_of_blocks', ['attribute' => $attribute]));

            return;
        }

        if ($decoded === []) {
            $fail(__('weave-blocknote::validation.blocknote_document.must_contain_one_block', ['attribute' => $attribute]));

            return;
        }

        foreach ($decoded as $block) {
            if (! is_array($block)) {
                $fail(__('weave-blocknote::validation.blocknote_document.block_must_be_object', ['attribute' => $attribute]));

                return;
            }
            if (! isset($block['type']) || ! is_string($block['type']) || $block['type'] === '') {
                $fail(__('weave-blocknote::validation.blocknote_document.block_must_have_type', ['attribute' => $attribute]));

                return;
            }
        }
    }
}
