<?php

namespace Manager;

use PlaceholderProcessor\Processor;
use Entity\Template;

class TemplateManager
{
    private Processor $processor;

    public function __construct()
    {
        $this->processor = new Processor();
    }

    /**
     * Take a template and array of data and return a template with content and subject
     * where variables have been replaced with actual content
     *
     * @param  Template $tpl
     * @param  array    $data
     * @return Template
     */
    public function getTemplateComputed(Template $tpl, array $data)
    {
        $tpl->setSubject($this->processor->process($tpl->getSubject(), $data));
        $tpl->setContent($this->processor->process($tpl->getContent(), $data));

        return $tpl;
    }
}
