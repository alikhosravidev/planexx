<?php

declare(strict_types=1);

if (! function_exists('to_persian')) {
    function to_persian($num): string
    {
        return \App\Utilities\Lang::toPersian($num);
    }
}
