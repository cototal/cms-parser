<?php

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

$tokens = [
    "[cms" => "open",
    "[/cms]" => "close",
    "/]" => "close"
];

$matches = null;
preg_match_all('/(\[cms|\[\/cms\]|\/\])/i', $sample, $matches, PREG_OFFSET_CAPTURE);

$collection = [];
$level = 0;
for ($idx = 0; $idx < count($matches[1]); $idx++) {
    $match = $matches[1][$idx][0];
    $token = $tokens[strtolower($match)];
    $position = $matches[1][$idx][1];
    $collection[] = [
        "match" => $match,
        "token" => $token,
        "position" => $position,
        "level" => $level
    ];

    $next = $matches[1][$idx + 1][0];
    $nextToken = $tokens[strtolower($next)];
    if ($token === "open" && $nextToken !== "close") {
        ++$level;
    } else if ($token === "close" && $nextToken !== "open") {
        --$level;
    }
}
var_dump($collection);
