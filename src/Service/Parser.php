<?php


namespace Cototal\CmsParser\Service;


use Cototal\CmsParser\Model\Config;
use Cototal\CmsParser\Model\Node;
use Cototal\CmsParser\Model\ParseResult;
use Cototal\CmsParser\Model\TagMeta;
use Cototal\CmsParser\Model\TagPosition;

class Parser
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $content = "";

    /**
     * @var array
     */
    private $tagHandlers;

    public function __construct(Config $config, $tagHandlers = [])
    {
        $this->config = $config;
        $this->tagHandlers = $tagHandlers;
    }

    public function parse($content)
    {
        $this->content = $content;
        $result = new ParseResult();
        $mappedTags = $this->mapTags();

        foreach ($mappedTags as $tag) {
            if (count($tag->getErrors()) > 0) {
                $result->addError("Error: tag at " . $tag->getStartPosition() . "has an error: " . implode(", ", $tag->getErrors()));
            }
        }

        if (count($result->getErrors()) > 0) {
            return $result;
        }

        foreach ($mappedTags as $tag) {
            $tagProps = $this->getTagProperties($tag);
            var_dump($tagProps);
        }
    }

    /**
     * @return TagPosition[]
     */
    private function collectTokens()
    {
        $tokens = $this->config->getTokens();
        $matches = null;
        preg_match_all($this->config->getMatcher(), $this->content, $matches, PREG_OFFSET_CAPTURE);
        // We only care about capture group results (though they are the same, in this case)
        $matches = $matches[1];

        $tagPositions = [];
        $level = 0;
        for ($idx = 0; $idx < count($matches); $idx++) {
            $match = $matches[$idx][0];
            $token = $tokens[strtolower($match)];
            $position = $matches[$idx][1];
            $tagPositions[] = (new TagPosition())
                ->setMatch($match)
                ->setLevel($level)
                ->setToken($token)
                ->setPosition($position);

            $nextIdx = $idx + 1;
            if ($nextIdx >= count($matches)) {
                break;
            }

            $next = $matches[$nextIdx][0];
            $nextToken = $tokens[strtolower($next)];
            if ($token === Config::TAG_OPEN && $nextToken !== Config::TAG_CLOSE) {
                ++$level;
            } else if ($token === Config::TAG_CLOSE && $nextToken !== Config::TAG_OPEN) {
                --$level;
            }
        }
        return $tagPositions;
    }

    /**
     * @return TagMeta[]
     */
    private function mapTags() {
        $tagPositions = $this->collectTokens();
        $openTags = array_values(array_filter($tagPositions, function($tp) {
            /** @var TagPosition $tp */
            return $tp->getToken() === Config::TAG_OPEN;
        }));
        $closeTags = array_values(array_filter($tagPositions, function($tp) {
            /** @var TagPosition $tp */
            return $tp->getToken() === Config::TAG_CLOSE;
        }));

        $collection = [];
        for ($idx = 0; $idx < count($openTags); $idx++) {
            /** @var TagPosition $tag */
            $tag = $openTags[$idx];
            $result = (new TagMeta())
                ->setStartPosition($tag->getPosition())
                ->setStartText($tag->getMatch());
            $closeTagIndex = array_search($tag->getLevel(), array_map(function($ct) {
                /** @var TagPosition $ct */
                return $ct->getLevel();
            }, $closeTags));

            if ($closeTagIndex === FALSE) {
                $result->addError("Unable to find closing tag.");
                $collection[] = $result;
                continue;
            }
            /** @var TagPosition $endTag */
            $endTag = $closeTags[$closeTagIndex];
            $result
                ->setEndPosition($endTag->getPosition())
                ->setEndText($endTag->getMatch());
            unset($closeTags[$closeTagIndex]);

            $collection[] = $result;
        }
        return $collection;
    }

    /**
     * @param TagMeta $tag
     * @return Node
     */
    private function getTagProperties($tag) {
        $openLabelLength = $this->config->getOpenLabelLength();
        $attrBlock = "";
        $inner = "";
        if ($tag->getEndText() === "/]") {
            $attrBlockLength = $tag->getEndPosition() - $tag->getStartPosition();
            $attrBlock = substr($this->content, $tag->getStartPosition() + $openLabelLength, $attrBlockLength - $openLabelLength);
        } else {
            $closingBracketPosition = strpos($this->content, "]", $tag->getStartPosition());
            $attrBlockLength = $closingBracketPosition - $tag->getStartPosition();
            $attrBlock = substr($this->content, $tag->getStartPosition() + $openLabelLength, $attrBlockLength - $openLabelLength);
            $sectionLength = $tag->getEndPosition() - $tag->getStartPosition() - $attrBlockLength - 1; // less 1  for the '[' position
            $inner = substr($this->content, $tag->getStartPosition() + $attrBlockLength + 1, $sectionLength);
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

        return (new Node)
            ->setAttributes($attributes)
            ->setInner($inner);
    }
}