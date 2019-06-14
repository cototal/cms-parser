<?php

namespace Cototal\CmsParser\Tests\Support;

use Cototal\CmsParser\Iface\ITagHandler;
use Cototal\CmsParser\Model\Node;

class SampleAccessHandler implements ITagHandler
{
    public function process(Node $node): string
    {
        return "<div class='access'>" . $node->getInner() . "</div>";
    }

    public function handles(Node $node): bool
    {
        if (array_key_exists("name", $node->getAttributes()) && $node->getAttributes()["name"] === "access") {
            return true;
        }
        return false;
    }
}