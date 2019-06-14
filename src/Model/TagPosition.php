<?php


namespace Cototal\CmsParser\Model;


class TagPosition
{
    /**
     * @var string
     */
    private $match;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $level;

    /**
     * @return string
     */
    public function getMatch(): string
    {
        return $this->match;
    }

    /**
     * @param string $match
     * @return TagPosition
     */
    public function setMatch(string $match): TagPosition
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return TagPosition
     */
    public function setToken(string $token): TagPosition
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return TagPosition
     */
    public function setPosition(int $position): TagPosition
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return TagPosition
     */
    public function setLevel(int $level): TagPosition
    {
        $this->level = $level;
        return $this;
    }
}