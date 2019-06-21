<?php

namespace Cototal\CmsParser\Tests\Support;

/**
 * From https://www.php.net/manual/en/class.iterator.php
 * Symfony DI uses an iterator to provide a collection of services via tags.
 */
class SampleIterator implements \Iterator
{
    private $position = 0;
    private $array = [];

    public function __construct(array $array) {
        $this->array = $array;
        $this->position = 0;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }
}