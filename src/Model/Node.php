<?php


namespace Cototal\CmsParser\Model;


class Node
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string
     */
    private $inner = "";

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return Node
     */
    public function setAttributes(array $attributes): Node
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getInner(): string
    {
        return $this->inner;
    }

    /**
     * @param string $inner
     * @return Node
     */
    public function setInner(string $inner): Node
    {
        $this->inner = $inner;
        return $this;
    }
}