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

    public function getIdHtml() {
        return '<p>' . $this->id . '</p>';
    }

    public function getIdString() {
        return (string) $this->id;
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
     * @param DateTime $dateQuoted
     */
    public function setDateQuoted(DateTime $dateQuoted): void
    {
        $this->dateQuoted = $dateQuoted;
    }
}
