<?php

return [

    'blocknote_document' => [
        'must_be_string' => 'Le champ :attribute doit être une chaîne de caractères.',
        'must_be_valid_json' => 'Le champ :attribute doit être un JSON valide.',
        'must_be_array_of_blocks' => 'Le champ :attribute doit être un tableau JSON de blocs.',
        'must_contain_one_block' => 'Le champ :attribute doit contenir au moins un bloc.',
        'block_must_be_object' => 'Chaque bloc dans :attribute doit être un objet.',
        'block_must_have_type' => 'Chaque bloc dans :attribute doit avoir un type non vide.',
    ],

];
