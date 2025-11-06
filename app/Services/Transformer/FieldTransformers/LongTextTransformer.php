<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Services\Transformer\FieldTransformers;

use App\Contracts\Transformer\FieldTransformerInterface;
use App\Utilities\TextAnalyzer;
use App\Utilities\TOC;

class LongTextTransformer implements FieldTransformerInterface
{
    public function transform($model): array
    {
        $analyzer = new TextAnalyzer($model);
        $toc      = new TOC();

        return [
            'short'      => $analyzer->shortWords(),
            'full'       => $model,
            'lines'      => $analyzer->lineCount(),
            'words'      => $analyzer->wordCount(),
            'read_times' => $analyzer->estimatedReadTime(),
            'toc_menus'  => $toc->generate($model),
        ];
    }
}
