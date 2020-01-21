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
            $site = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            if(isset($data['user']) && $data['user'] instanceof User) {
                $user = $data['user'];
            } else {
                $user = $applicationContext->getCurrentUser();
            }

            if (strpos($text, '[quote:summary_html]')) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($quote),
                    $text
                );
            }
            if (strpos($text, '[quote:summary]')) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($quote),
                    $text
                );
            }

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]', $destination->getCountryName(), $text);


            if (isset($destination))
                $text = str_replace('[quote:destination_link]', $site->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $quote->getId(), $text);
            else
                $text = str_replace('[quote:destination_link]', '', $text);

            if ($user) {
                (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($_user->getFirstname())), $text);
            }
        }

        return $text;
    }
}
