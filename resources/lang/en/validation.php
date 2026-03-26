<?php

return [

    'blocknote_document' => [
        'must_be_string' => 'The :attribute must be a string.',
        'must_be_valid_json' => 'The :attribute must be valid JSON.',
        'must_be_array_of_blocks' => 'The :attribute must be a JSON array of blocks.',
        'must_contain_one_block' => 'The :attribute must contain at least one block.',
        'block_must_be_object' => 'Each block in :attribute must be an object.',
        'block_must_have_type' => 'Each block in :attribute must have a non-empty type.',
    ],

];
