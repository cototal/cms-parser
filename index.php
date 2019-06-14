<?php

require "vendor/autoload.php";

$sample = <<<SAMPLE
<div class="container">
    <h1>This is a test</h1>
    [cms name="access" ids="1,2,3"]
    <ul>
        <li>
            [CMS name="document" id="1"][/cms]
            [cms name="document" id="2"/]    
        </li>
        <li>
        
        </li>
    </ul>
    [/cms]
    <p>
        Outside the access
        [cms name="document" id="3" /]
    </p>
</div>
SAMPLE;

class SampleAccessHandler implements \Cototal\CmsParser\Iface\ITagHandler {
    public function process(\Cototal\CmsParser\Model\Node $node): string
    {
        return "<div class='access'>" . $node->getInner() . "</div>";
    }

    public function handles(\Cototal\CmsParser\Model\Node $node): bool
    {
        if (array_key_exists("name", $node->getAttributes()) && $node->getAttributes()["name"] === "access") {
            return true;
        }
        return false;
    }
}

class SampleDocumentHandler implements \Cototal\CmsParser\Iface\ITagHandler {
    public function process(\Cototal\CmsParser\Model\Node $node): string
    {
        $label = $node->getInner();
        if (empty($label)) {
            $label = "Document Name";
        }
        return "<a href='#'>$label</a>";
    }

    public function handles(\Cototal\CmsParser\Model\Node $node): bool
    {
        if (array_key_exists("name", $node->getAttributes()) && $node->getAttributes()["name"] === "document") {
            return true;
        }
        return false;
    }
}

$config = new \Cototal\CmsParser\Model\Config();
$factory = new \Cototal\CmsParser\Service\TagHandlerFactory([new SampleDocumentHandler()]);
$factory->addTagHandler(new SampleAccessHandler());
$parser = new \Cototal\CmsParser\Service\Parser($config, $factory);

var_dump($parser->parse($sample));