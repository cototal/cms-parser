<?php


namespace Cototal\CmsParser\Service;


use Cototal\CmsParser\Iface\ITagHandler;
use Cototal\CmsParser\Model\Node;

class TagHandlerFactory
{
    /**
     * @var ITagHandler[]
     */
    private $tagHandlers;

    public function __construct(array $tagHandlers)
    {
        for ($idx = 0; $idx < count($tagHandlers); $idx++) {
            $handler = $tagHandlers[$idx];
            if (!$handler instanceof ITagHandler) {
                throw new \InvalidArgumentException("Handler ${idx} is not a valid ITagHandler");
            }
        }

        $this->tagHandlers = $tagHandlers;
    }

    /**
     * @param Node $node
     * @return ITagHandler
     */
    public function handlerFor(Node $node)
    {
        foreach ($this->tagHandlers as $handler) {
            if ($handler->handles($node)) {
                return $handler;
            }
        }

        throw new \InvalidArgumentException("No tag handler for node: " . (string)$node);
    }

    public function addTagHandler(ITagHandler $tagHandler)
    {
        $this->tagHandlers[] = $tagHandler;
    }
}