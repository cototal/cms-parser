<?php


namespace Cototal\CmsParser\Iface;


use Cototal\CmsParser\Model\Node;

interface ITagHandler
{
    public function process(Node $node): string;
    public function handles(Node $node): bool;
}