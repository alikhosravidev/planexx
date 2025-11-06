<?php

declare(strict_types=1);

namespace App\Utilities;

class TOC
{
    /**
     * @var array stores the extracted headings from the HTML
     */
    private array $headings = [];

    /**
     * @var array the final table of contents structure
     */
    private array $toc = [];

    /**
     * @var array stack to manage the current position in the TOC structure
     */
    private array $stack = [];

    /**
     * @var int the level of the previous heading processed
     */
    private int $prevLevel = 0;

    /**
     * Generates the table of contents from the provided HTML string.
     *
     * @param string $html the HTML content to parse
     *
     * @return array the generated table of contents
     */
    public function generate(string $html): array
    {
        $this->extractHeadings($html);
        $this->buildTOCStructure();

        return $this->toc;
    }

    /**
     * Extracts headings from the HTML content.
     *
     * @param string $html the HTML content to parse
     */
    private function extractHeadings(string $html): void
    {
        preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/i', $html, $matches, PREG_SET_ORDER);
        $this->headings = $matches;
    }

    /**
     * Builds the table of contents structure from the extracted headings.
     */
    private function buildTOCStructure(): void
    {
        $this->initializeStack();

        foreach ($this->headings as $heading) {
            $level = $this->getHeadingLevel($heading);
            $item  = $this->createTOCItem($heading, $level);
            $this->addItemToStructure($item, $level);
        }
    }

    /**
     * Initializes the stack and previous level for TOC building.
     */
    private function initializeStack(): void
    {
        $this->stack     = [&$this->toc];
        $this->prevLevel = 0;
    }

    /**
     * Gets the level of the heading.
     *
     * @param array $heading the heading match from regex
     *
     * @return int the level of the heading
     */
    private function getHeadingLevel(array $heading): int
    {
        return (int) $heading[1];
    }

    /**
     * Creates a TOC item from the heading.
     *
     * @param array $heading the heading match from regex
     * @param int   $level   the level of the heading
     *
     * @return array the TOC item
     */
    private function createTOCItem(array $heading, int $level): array
    {
        return [
            'head'     => 'h' . $level,
            'title'    => $this->cleanTitle($heading[2]),
            'children' => [],
        ];
    }

    /**
     * Cleans the title by stripping HTML tags.
     *
     * @param string $title the title to clean
     *
     * @return string the cleaned title
     */
    private function cleanTitle(string $title): string
    {
        return strip_tags($title);
    }

    /**
     * Adds an item to the TOC structure.
     *
     * @param array $item  the TOC item to add
     * @param int   $level the level of the heading
     */
    private function addItemToStructure(array $item, int $level): void
    {
        $this->adjustStackForNewLevel($level);
        $this->appendItemToCurrentLevel($item);
        $this->updateStackAndPrevLevel($item, $level);
    }

    /**
     * Adjusts the stack for the new heading level.
     *
     * @param int $level the level of the heading
     */
    private function adjustStackForNewLevel(int $level): void
    {
        while ($level <= $this->prevLevel && \count($this->stack) > 1) {
            array_pop($this->stack);
            --$this->prevLevel;
        }
    }

    /**
     * Appends the item to the current level in the TOC structure.
     *
     * @param array $item the TOC item to append
     */
    private function appendItemToCurrentLevel(array $item): void
    {
        $currentLevel   = &$this->stack[\count($this->stack) - 1];
        $currentLevel[] = $item;
    }

    /**
     * Updates the stack and previous level after adding an item.
     *
     * @param array $item  the TOC item added
     * @param int   $level the level of the heading
     */
    private function updateStackAndPrevLevel(array $item, int $level): void
    {
        $this->stack[]   = &$this->stack[\count($this->stack) - 1][\count($this->stack[\count($this->stack) - 1]) - 1]['children'];
        $this->prevLevel = $level;
    }
}
