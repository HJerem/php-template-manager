<?php

namespace Manager;

use Context\ApplicationContext;
use Entity\Quote;
use Entity\User;
use Helper\TextHelper;
use Repository\DestinationRepository;
use Entity\Template;
use Repository\SiteRepository;
use RuntimeException;

class TemplateManager
{
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
        $replaced = clone($tpl);
        $replaced->setSubject($this->computeText($replaced->getSubject(), $data));
        $replaced->setContent($this->computeText($replaced->getContent(), $data));

        return $replaced;
    }

    /**
     * Replace variables in a text by values based on $data array content
     *
     * @param  string $text
     * @param  array  $data
     * @return string|string[]
     */
    private function computeText(string $text, array $data)
    {

        if (isset($data['quote']) && $data['quote'] instanceof Quote) {
            $quote = $data['quote'];
            $site = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            // get passed user or get current user
            if (isset($data['user']) && $data['user'] instanceof User) {
                $user = $data['user'];
            } else {
                $applicationContext = ApplicationContext::getInstance();
                $user = $applicationContext->getCurrentUser();
            }

            if (strpos($text, '[quote:summary_html]')) {
                $text = TextHelper::searchAndReplace('[quote:summary_html]', Quote::renderHtml($quote), $text);
            }
            if (strpos($text, '[quote:summary]')) {
                $text = TextHelper::searchAndReplace('[quote:summary]', Quote::renderText($quote), $text);
            }

            $text = TextHelper::searchAndReplace(
                '[quote:destination_link]',
                $site->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $quote->getId(),
                $text
            );
            $text = TextHelper::searchAndReplace('[quote:destination_name]', $destination->getCountryName(), $text);

            if ($user) {
                $text = TextHelper::searchAndReplace('[user:first_name]', $user->getFirstname(), $text);
            }
        }

        return $text;
    }
}
