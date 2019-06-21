# CMS Parser

Translate tags you can expose in your CMS into HTML. This will work similarly to
[WordPress shortcodes](https://en.support.wordpress.com/shortcodes/), but all tags should use the same
label, which is `cms` by default. They should also provide a `name` attribute or some other way to identify
them for the tag handlers that you set up.

For example, you could restrict content visibility to a particular group:

    This is a [cms name="group" roles="group 1"]sample[/cms].
    
You would define a tag handler to handle tags named `group` and then use the access controls for your app to determine
if the current user has the role `group 1`. If the user does have access, the `[cms]` code disappears and just
`sample` is displayed. If the user does not have access, the whole thing disappears and they would see `This is a .`.

## With Symfony

The `TagHandlerFactory` should be injectable by tagging services as described in the
[Reference Tagged Services](https://symfony.com/doc/current/service_container/tags.html#reference-tagged-services)

### Example for Symfony 4

In `config/services.yaml`:

```yaml
Cototal\CmsParser\Service\TagHandlerFactory:
    public: true
    arguments: [!tagged cms.tag_handler]

Cototal\CmsParser\Service\Parser:
    public: true

# It's necessary to specify this class explicitly so that autowiring works for the Parser service
Cototal\CmsParser\Model\Config:
    public: true

# Setup tag handlers in a separate 'TagHandler' directory
App\TagHandler\:
    resource: "../src/TagHandler"
    tags: [cms.tag_handler]
```

There are some sample tag handlers in the `tests/Support` directory.

## Twig

In Symfony, you can create a [custom twig extension](https://symfony.com/doc/current/templating/twig_extension.html)
and use that for parsing the cms contents in a twig file. For example:

```php
<?php

namespace App\Twig;

use Psr\Log\LoggerInterface;
use Cototal\CmsParser\Model\ParseResult;
use Cototal\CmsParser\Service\Parser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ParserExtension extends AbstractExtension
{
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, Parser $parser)
    {
        $this->parser = $parser;
        $this->logger = $logger;
    }

    public function getFilters()
    {
        return [
            new TwigFilter("cms", [$this, "parseContent"])
        ];
    }

    public function parseContent(string $content)
    {
        $parseResult = new ParseResult();
        try {
            $parseResult = $this->parser->parse($content);
        } catch (\InvalidArgumentException $ex) {
            $parseResult->addError($ex->getMessage());
        }
        if (count($parseResult->getErrors()) > 0) {
            $this->logger->error("Error parsing CMS content", $parseResult->getErrors());
            return $content;
        }
        return $parseResult->getPayload();
    }
}
```

Load this with the `twig.extension` tag in `config/services.yml`

```yaml
App\Twig\ParserExtension:
    tags: [twig.extension]
```

Then you can use it in your twig templates:

```
{% for section in sectionsTL["left"] %}
    {{ section.content | cms | raw }}
{% endfor %}
```