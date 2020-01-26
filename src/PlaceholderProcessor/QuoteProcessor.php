<?php

namespace PlaceholderProcessor;

use Entity\Quote;
use Helper\TextHelper;
use Repository\DestinationRepository;
use Repository\SiteRepository;

class QuoteProcessor implements ProcessorInterface
{
    private DestinationRepository $destinationRepository;
    private SiteRepository $siteRepository;

    const QUOTE_SUMMARY_HTML = '[quote:summary_html]';
    const QUOTE_SUMMARY = '[quote:summary]';
    const QUOTE_DESTINATION_NAME = '[quote:destination_name]';
    const QUOTE_DESTINATION_LINK = '[quote:destination_link]';

    public function __construct()
    {
        $this->destinationRepository = DestinationRepository::getInstance();
        $this->siteRepository = SiteRepository::getInstance();
    }

    public function replacePlaceholders(string $text, array $data)
    {
        if(isset($data['quote']) && $data['quote'] instanceof Quote) {
            return $this->getTextWithValues($text, $data['quote']);
        }

        return $text;
    }

    protected function getTextWithValues(string $text, Quote $quote) {
        $site = $this->siteRepository->getById($quote->getSiteId());
        $destination = $this->destinationRepository->getById($quote->getDestinationId());

        if(TextHelper::doesContain(self::QUOTE_SUMMARY_HTML, $text)) {
            $text = TextHelper::searchAndReplace(self::QUOTE_SUMMARY_HTML, $quote->getIdHtml(), $text);
        }
        if(TextHelper::doesContain(self::QUOTE_SUMMARY, $text)) {
            $text = TextHelper::searchAndReplace(self::QUOTE_SUMMARY, $quote->getIdString(), $text);
        }
        if(TextHelper::doesContain(self::QUOTE_DESTINATION_NAME, $text)) {
            $text = TextHelper::searchAndReplace(self::QUOTE_DESTINATION_NAME, $destination->getCountryName(), $text);
        }
        if(TextHelper::doesContain(self::QUOTE_DESTINATION_LINK, $text)) {
            $text = TextHelper::searchAndReplace(self::QUOTE_DESTINATION_LINK, $site->getUrl() . $destination->getCountryName() . '/quote/' . $quote->getId(), $text);
        }

        return $text;
    }
}