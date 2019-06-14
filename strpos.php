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

$open = "[cms";
$close = "[/cms]";

$nextStart = strpos($sample, $open);
$collection = [];
$level = 0;
while ($nextStart !== FALSE) {
    $element = ["level" => $level, "startPosition" => $nextStart];
    var_dump("START $nextStart");
    $nextEnd = strpos($sample, $close, $nextStart);
    var_dump("NXEND $nextEnd");
    $selfEnd = strpos($sample, "/]", $nextStart);
    var_dump("SFEND $selfEnd");
    $nextStart = strpos($sample, $open, $nextStart + 1);
    $foundEnd = null;
    if ($nextEnd === FALSE && $selfEnd === FALSE) {
        $element["error"] = "No end tag found";
    } else if ($nextEnd && $selfEnd === FALSE) {
        $foundEnd = $nextEnd;
    } else if ($selfEnd && $nextEnd === FALSE) {
        $foundEnd = $selfEnd;
    } else {
        $foundEnd = $nextEnd < $selfEnd ? $nextEnd : $selfEnd;
    }
    var_dump("FOUND $foundEnd");

    if ($nextStart === FALSE || $foundEnd < $nextStart) {
        $element["endPosition"] = $foundEnd;
    } else {
        ++$level;
    }
    $collection[] = $element;
}

var_dump($collection);