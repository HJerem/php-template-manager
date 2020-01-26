<?php

namespace PlaceholderProcessor;

class Processor
{
    private array $processors = [];

    public function __construct()
    {
        $this->processors[] = new QuoteProcessor();
        $this->processors[] = new UserProcessor();
    }

    public function process(string $text, array $data)
    {
        foreach ($this->processors as $processor) {
            $text = $processor->replacePlaceholders($text, $data);
        }
        return $text;
    }
}
