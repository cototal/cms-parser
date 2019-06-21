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
     * @var TagMeta|null
     */
    private $tagMeta;

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

    /**
     * @return TagMeta|null
     */
    public function getTagMeta(): ?TagMeta
    {
        return $this->tagMeta;
    }

    /**
     * @param TagMeta|null $tagMeta
     * @return Node
     */
    public function setTagMeta(?TagMeta $tagMeta): Node
    {
        $this->tagMeta = $tagMeta;
        return $this;
    }

    public function getName()
    {
        if (array_key_exists("name", $this->attributes)) {
            return $this->attributes["name"];
        }

        return "unnamed";
    }

    public function __toString()
    {
        return $this->getName() . " node";
    }
}