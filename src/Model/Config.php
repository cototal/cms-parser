<?php


namespace Cototal\CmsParser\Model;


class Config
{
    /**
     * @var string
     */
    private $tagName = "cms";

    /**
     * @var array
     */
    private $config = [];

    public function __construct(?string $configFileName = null)
    {
        // Ignore if no config file
        if ($configFileName && file_exists($configFileName) && is_readable($configFileName)) {
            $data = json_decode(file_get_contents($configFileName), true);
            // Ignore if JSON decode fails
            if (is_array($data)) {
                $this->config = $data;
            }
        }
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        if (array_key_exists("tagName", $this->config)) {
            return $this->config["tagName"];
        }

        return $this->tagName;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        $tagName = $this->getTagName();
        return [
            "[$tagName" => "open",
            "[/$tagName]" => "close",
            "/]" => "close"
        ];
    }
}