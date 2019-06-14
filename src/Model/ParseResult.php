<?php


namespace Cototal\CmsParser\Model;


class ParseResult
{
    /**
     * @var string|null
     */
    private $payload;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return string|null
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @param string|null $payload
     * @return ParseResult
     */
    public function setPayload(?string $payload): ParseResult
    {
        $this->payload = $payload;
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
     * @return ParseResult
     */
    public function setErrors(array $errors): ParseResult
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $error
     * @return ParseResult
     */
    public function addError(string $error): ParseResult
    {
        $this->errors[] = $error;
        return $this;
    }
}