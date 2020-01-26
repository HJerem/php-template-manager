<?php

namespace Manager;

use Context\ApplicationContext;
use Entity\Quote;
use Entity\User;
use Helper\TextHelper;
use Processor\Processor;
use Repository\DestinationRepository;
use Entity\Template;
use Repository\SiteRepository;

class TemplateManager
{
    private Processor $processor;

    public function __construct()
    {
        $this->processor = Processor::getInstance();
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
