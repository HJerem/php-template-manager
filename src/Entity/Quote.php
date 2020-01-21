<?php

namespace Entity;

use DateTime;

class Quote
{
    private int $id;
    private string $siteId;
    private string $destinationId;
    private DateTime $dateQuoted;

    public function __construct(int $id, int $siteId, int $destinationId, DateTime $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote)
    {
        return '<p>' . $quote->id . '</p>';
    }

    public static function renderText(Quote $quote)
    {
        return (string) $quote->id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int|string $siteId
     */
    public function setSiteId($siteId): void
    {
        $this->siteId = $siteId;
    }

    /**
     * @return int|string
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * @param int|string $destinationId
     */
    public function setDestinationId($destinationId): void
    {
        $this->destinationId = $destinationId;
    }

    /**
     * @return string
     */
    public function getDateQuoted(): string
    {
        return $this->dateQuoted;
    }

    /**
     * @param string $dateQuoted
     */
    public function setDateQuoted(string $dateQuoted): void
    {
        $this->dateQuoted = $dateQuoted;
    }

}