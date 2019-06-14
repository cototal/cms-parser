<?php

namespace Cototal\CmsParser\Tests\Support;

use Cototal\CmsParser\Iface\ITagHandler;
use Cototal\CmsParser\Model\Node;

class SampleDocumentHandler implements ITagHandler
{
    public function process(Node $node): string
    {
        $label = $node->getInner();
        if (empty($label)) {
            $label = "Document Name";
        }
        return "<a href='#'>$label</a>";
    }

    public function handles(Node $node): bool
    {
        if (array_key_exists("name", $node->getAttributes()) && $node->getAttributes()["name"] === "document") {
            return true;
        }
        return false;
    }
}