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

function tokens() {
    return [
        "[cms" => "open",
        "[/cms]" => "close",
        "/]" => "close"
    ];
}

function collectTokens($content) {
    $tokens = tokens();
    $matches = null;
    preg_match_all('/(\[cms|\[\/cms\]|\/\])/i', $content, $matches, PREG_OFFSET_CAPTURE);

    $tagPositions = [];
    $level = 0;
    for ($idx = 0; $idx < count($matches[1]); $idx++) {
        $match = $matches[1][$idx][0];
        $token = $tokens[strtolower($match)];
        $position = $matches[1][$idx][1];
        $tagPositions[] = [
            "match" => $match,
            "token" => $token,
            "position" => $position,
            "level" => $level
        ];

        $nextIdx = $idx + 1;
        if ($nextIdx >= count($matches[1])) {
            break;
        }

        $next = $matches[1][$nextIdx][0];
        $nextToken = $tokens[strtolower($next)];
        if ($token === "open" && $nextToken !== "close") {
            ++$level;
        } else if ($token === "close" && $nextToken !== "open") {
            --$level;
        }
    }
    return $tagPositions;
}

function mapTags($content) {
    $tagPositions = collectTokens($content);
    $openTags = array_values(array_filter($tagPositions, function($tp) {
        return $tp["token"] === "open";
    }));
    $closeTags = array_values(array_filter($tagPositions, function($tp) {
        return $tp["token"] === "close";
    }));

    $collection = [];
    for ($idx = 0; $idx < count($openTags); $idx++) {
        $tag = $openTags[$idx];
        $result = [];
        $result["startPosition"] = $tag["position"];
        $result["startText"] = $tag["match"];
        $closeTagIndex = array_search($tag["level"], array_map(function($ct) { return $ct["level"]; }, $closeTags));
        if ($closeTagIndex === FALSE) {
            $result["error"] = "Unable to find closing tag.";
            $collection[] = $result;
            continue;
        }
        $endTag = $closeTags[$closeTagIndex];
        $result["endPosition"] = $endTag["position"];
        $result["endText"] = $endTag["match"];
        unset($closeTags[$closeTagIndex]);

        $collection[] = $result;
    }
    return $collection;
}

function parseTags($content) {
    $mappedTags = mapTags($content);
    foreach ($mappedTags as $tag) {
        if (array_key_exists("error", $tag)) {
            return "Error: tag at " . $tag["startPosition"] . "has an error: " . $tag["error"];
        }
    }

    foreach ($mappedTags as $tag) {
        $tagProps = getTagProperties($content, $tag);
        var_dump($tagProps);
    }
}

function getTagProperties($content, $tag) { // Strips off the closing tag
    $openLabelLength = strlen("[cms ");
    $attrBlock = "";
    $inner = "";
    if ($tag["endText"] === "/]") {
        $attrBlockLength = $tag["endPosition"] - $tag["startPosition"];
        $attrBlock = substr($content, $tag["startPosition"] + $openLabelLength, $attrBlockLength - $openLabelLength);
    } else {
        $closingBracketPosition = strpos($content, "]", $tag["startPosition"]);
        $attrBlockLength = $closingBracketPosition - $tag["startPosition"];
        $attrBlock = substr($content, $tag["startPosition"] + $openLabelLength, $attrBlockLength - $openLabelLength);
        $sectionLength = $tag["endPosition"] - $tag["startPosition"] - $attrBlockLength - 1; // less 1  for the '[' position
        $inner = substr($content, $tag["startPosition"] + $attrBlockLength + 1, $sectionLength);
    }

    $attrGroups = explode(" ", $attrBlock);
    $attributes = [];
    foreach ($attrGroups as $group) {
        if (!strpos($group, "=")) {
            continue;
        }
        $keyValue = explode("=", $group);
        $attributes[trim($keyValue[0], " ")] = trim($keyValue[1], " \"'");
    }

    return [
        "attributes" => $attributes,
        "inner" => $inner
    ];
}

parseTags($sample);
die();