<?php

declare(strict_types=1);

use App\Services\Transformer\FieldTransformers;

return [
    /*
    |--------------------------------------------------------------------------
    | Transformer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the transformer system including default field
    | transformers, blacklisted fields, and other settings.
    |
    */

    'field_transformers' => [
        'created_at'       => FieldTransformers\DateTimeTransformer::class,
        'updated_at'       => FieldTransformers\DateTimeTransformer::class,
        'deleted_at'       => FieldTransformers\DateTimeTransformer::class,
        'completed_at'     => FieldTransformers\DateTimeTransformer::class,
        'visited_at'       => FieldTransformers\DateTimeTransformer::class,
        'expires_at'       => FieldTransformers\DateTimeTransformer::class,
        'deadline'         => FieldTransformers\DateTimeTransformer::class,
        'due_date'         => FieldTransformers\DateTimeTransformer::class,
        'description'      => FieldTransformers\LongTextTransformer::class,
        'text'             => FieldTransformers\LongTextTransformer::class,
        'caption'          => FieldTransformers\LongTextTransformer::class,
        'body'             => FieldTransformers\LongTextTransformer::class,
        'article'          => FieldTransformers\LongTextTransformer::class,
        'content'          => FieldTransformers\LongTextTransformer::class,
        'long_description' => FieldTransformers\LongTextTransformer::class,
        'amount'           => FieldTransformers\PriceTransformer::class,
        'price'            => FieldTransformers\PriceTransformer::class,
        'price_percent'    => FieldTransformers\PriceTransformer::class,
        'final_price'      => FieldTransformers\PriceTransformer::class,
        'total_price'      => FieldTransformers\PriceTransformer::class,
    ],

    'blacklisted_fields' => [
        'password',
        'password_hash',
        'remember_token',
        'secret',
        'secret_field',
        'api_token',
        'token',
    ],

    'available_includes' => [],

    'default_includes' => [],

    'include_accessors' => true,
];
