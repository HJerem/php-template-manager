<?php

namespace Manager;

use Context\ApplicationContext;
use Entity\Quote;
use Entity\User;
use Repository\DestinationRepository;
use Entity\Template;
use Repository\QuoteRepository;
use Repository\SiteRepository;
use RuntimeException;

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->setSubject($this->computeText($replaced->getSubject(), $data));
        $replaced->setContent($this->computeText($replaced->getContent(), $data));

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $applicationContext = ApplicationContext::getInstance();

        if (isset($data['quote']) && $data['quote'] instanceof Quote) {
            $quote = $data['quote'];
            $usefulObject = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            if (strpos($text, '[quote:destination_link]') !== false) {
                $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());
            }

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[quote:summary_html]',
                        Quote::renderHtml($quote),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[quote:summary]',
                        Quote::renderText($quote),
                        $text
                    );
                }
            }

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]', $destinationOfQuote->getCountryName(), $text);


            if (isset($destination))
                $text = str_replace('[quote:destination_link]', $usefulObject->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $quote->getId(), $text);
            else
                $text = str_replace('[quote:destination_link]', '', $text);

            /*
             * USER
             * [user:*]
             */
            $_user = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $applicationContext->getCurrentUser();
            if ($_user) {
                (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($_user->getFirstname())), $text);
            }
        }

        return $text;
    }
}
