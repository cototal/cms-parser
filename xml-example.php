<?php

$sample = <<<SAMPLE
<form id="search" action="/search" method="get" class="searchbar js-searchbar " autocomplete="off" role="search">
        <div class="ps-relative">
            <input name="q" type="text" placeholder="Search…" value="" autocomplete="off" maxlength="240" class="s-input s-input__search js-search-field " />
            <svg aria-hidden="true" class="svg-icon s-input-icon s-input-icon__search iconSearch" width="18" height="18" viewBox="0 0 18 18"><path d="M18 16.5l-5.14-5.18h-.35a7 7 0 1 0-1.19 1.19v.35L16.5 18l1.5-1.5zM12 7A5 5 0 1 1 2 7a5 5 0 0 1 10 0z"/></svg>
        </div>
</form>
SAMPLE;

$xml = simplexml_load_string($sample);
$svgs = $xml->xpath("//svg");
$div = new SimpleXMLElement("<div>This is a sample</div>");
foreach ($svgs as $svg) {
    $attrs = [];
    foreach ($svg->attributes() as $key => $val) {
        $attrs[] = "$key='$val'";
    }
    $innerHtml = $svg->children()->asXML();
    $attrString = implode(" ", $attrs);
    $sample = str_replace($svg->asXML(), "<div $attrString>$innerHtml</div>", $sample);
}

var_dump($sample);

echo "derp" . PHP_EOL;