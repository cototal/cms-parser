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

$config = new \Cototal\CmsParser\Model\Config();
$parser = new \Cototal\CmsParser\Service\Parser($config);

$parser->parse($sample);