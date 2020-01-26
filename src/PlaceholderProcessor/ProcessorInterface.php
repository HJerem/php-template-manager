<?php

namespace PlaceholderProcessor;

interface ProcessorInterface
{
    public function replacePlaceholders(string $text, array $data);
}
