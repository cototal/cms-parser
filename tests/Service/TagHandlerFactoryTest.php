<?php

namespace Cototal\CmsParser\Tests\Service;

use PHPUnit\Framework\TestCase;
use Cototal\CmsParser\Model\Node;
use Cototal\CmsParser\Service\TagHandlerFactory;
use Cototal\CmsParser\Tests\Support\SampleDocumentHandler;
use Cototal\CmsParser\Tests\Support\SampleIterator;

class TagHandlerFactoryTest extends TestCase
{
    public function testCanBeGivenAnArray()
    {
        $factory = new TagHandlerFactory([new SampleDocumentHandler()]);
        $node = (new Node)->setAttributes(["name" => "document"]);
        $handler = $factory->handlerFor($node);
        $this->assertInstanceOf(SampleDocumentHandler::class, $handler);
    }

    public function testCanBeGivenAnIterator()
    {
        $iterator = new SampleIterator([new SampleDocumentHandler()]);
        $factory = new TagHandlerFactory($iterator);
        $node = (new Node)->setAttributes(["name" => "document"]);
        $handler = $factory->handlerFor($node);
        $this->assertInstanceOf(SampleDocumentHandler::class, $handler);
    }

    public function testThrowsErrorIfGivenInvalidTagHandlers()
    {
        $this->expectException(\InvalidArgumentException::class);
        new TagHandlerFactory(0);
    }

    public function testHandlersCanBeAddedLater()
    {
        $factory = new TagHandlerFactory([]);
        $factory->addTagHandler(new SampleDocumentHandler());
        $node = (new Node)->setAttributes(["name" => "document"]);
        $handler = $factory->handlerFor($node);
        $this->assertInstanceOf(SampleDocumentHandler::class, $handler);
    }
}