<?php

namespace Processor;

interface ProcessorInterface {
    public function replacePlaceholders(string $text, array $data);
}