<?php

namespace Manager;

use Context\ApplicationContext;
use Entity\Quote;
use Entity\User;
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
        $applicationContext = ApplicationContext::getInstance();

        if (isset($data['quote']) && $data['quote'] instanceof Quote) {
            $quote = $data['quote'];
            $site = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            // get passed user or get current user
            if (isset($data['user']) && $data['user'] instanceof User) {
                $user = $data['user'];
            } else {
                $user = $applicationContext->getCurrentUser();
            }

            if (strpos($text, '[quote:summary_html]')) {
                $this->searchAndReplace('[quote:summary_html]', Quote::renderHtml($quote), $text);
            }
            if (strpos($text, '[quote:summary]')) {
                $this->searchAndReplace('[quote:summary]', Quote::renderText($quote), $text);
            }

            $this->searchAndReplace(
                '[quote:destination_link]',
                $site->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $quote->getId(),
                $text
            );
            $this->searchAndReplace('[quote:destination_name]', $destination->getCountryName(), $text);

            if ($user) {
                $this->searchAndReplace('[user:first_name]', $user->getFirstname(), $text);
            }
        }

        return $text;
    }

    /**
     * Search a $needle and replace with $replace in a $text passed as reference
     *
     * @param  string $needle
     * @param  string $replace
     * @param  string $text
     * @return string|string[]
     */
    private function searchAndReplace(string $needle, string $replace, string &$text)
    {
        $text = str_replace($needle, $replace, $text);
        return $text;
    }
}
