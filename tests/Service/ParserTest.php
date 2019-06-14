<?php

namespace Cototal\CmsParser\Tests\Service;

use Cototal\CmsParser\Model\Config;
use Cototal\CmsParser\Service\Parser;
use Cototal\CmsParser\Service\TagHandlerFactory;
use Cototal\CmsParser\Tests\Support\SampleAccessHandler;
use Cototal\CmsParser\Tests\Support\SampleDocumentHandler;
use \PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParseRemovesCmsTags()
    {
        $config = new Config();
        $factory = new TagHandlerFactory([new SampleDocumentHandler()]);
        $factory->addTagHandler(new SampleAccessHandler());
        $parser = new Parser($config, $factory);
        $sample = $this->sample();
        $parseResult = $parser->parse($sample);
        $this->assertEquals([true, false], [!!strpos($sample, "cms"), strpos($parseResult->getPayload(), "cms")]);
    }

    private function sample()
    {
        return <<<SAMPLE
<div class="container">
    <h1>This is a tests</h1>
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
    }
}