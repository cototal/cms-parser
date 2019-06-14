<?php


namespace Cototal\CmsParser\Model;


class TagMeta
{
    /**
     * @var int
     */
    private $startPosition;

    /**
     * @var string
     */
    private $startText;

    /**
     * @var int|null
     */
    private $endPosition;

    /**
     * @var string|null
     */
    private $endText;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return int
     */
    public function getStartPosition(): int
    {
        return $this->startPosition;
    }

    /**
     * @param int $startPosition
     * @return TagMeta
     */
    public function setStartPosition(int $startPosition): TagMeta
    {
        $this->startPosition = $startPosition;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartText(): string
    {
        return $this->startText;
    }

    /**
     * @param string $startText
     * @return TagMeta
     */
    public function setStartText(string $startText): TagMeta
    {
        $this->startText = $startText;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEndPosition(): ?int
    {
        return $this->endPosition;
    }

    /**
     * @param int|null $endPosition
     * @return TagMeta
     */
    public function setEndPosition(?int $endPosition): TagMeta
    {
        $this->endPosition = $endPosition;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndText(): ?string
    {
        return $this->endText;
    }

    /**
     * @param string|null $endText
     * @return TagMeta
     */
    public function setEndText(?string $endText): TagMeta
    {
        $this->endText = $endText;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return TagMeta
     */
    public function setErrors(array $errors): TagMeta
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $error
     * @return TagMeta
     */
    public function addError(string $error): TagMeta
    {
        $this->errors[] = $error;
        return $this;
    }
}