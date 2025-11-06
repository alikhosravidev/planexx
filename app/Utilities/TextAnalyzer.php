<?php

declare(strict_types=1);

namespace App\Utilities;

namespace App\Utilities;

class TextAnalyzer
{
    private string $raw;

    public function __construct(private string $text)
    {
        $this->raw = stripslashes(strip_tags($text));
    }

    public function normalized(): string
    {
        return str_replace(["\r", "\n", "\t", '&nbsp;'], ' ', $this->raw);
    }

    public function wordCount(): int
    {
        return str_word_count($this->normalized(), 0, 'ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی');
    }

    public function shortWords(int $limit = 20): string
    {
        return implode(' ', array_slice(explode(' ', $this->normalized()), 0, $limit));
    }

    public function lineCount(): int
    {
        preg_match_all("/\n|<\\/br>/u", $this->text, $matches);

        return count($matches[0]) + 1;
    }

    public function estimatedReadTime(int $wpm = 200): int
    {
        return (int) ceil($this->wordCount() / $wpm);
    }
}
