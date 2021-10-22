<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Traits;

use PHPUnit\Framework\TestCase;

class ExpanderTest extends TestCase
{
    public function test_expand_url()
    {
        $expander = $this->getObjectForTrait(Expander::class);
        $template = "https://lorem.com/{test}/{fragment}";
        $expandedTemplate = $expander->expandUrl($template, ['test' => "losabim", "fragment" => "oxygenium"]);

        $this->assertEquals("https://lorem.com/losabim/oxygenium", $expandedTemplate);
    }

    public function test_expand_url_with_missing_data()
    {
        $expander = $this->getObjectForTrait(Expander::class);
        $template = "https://lorem.com/{test}/{fragment}";
        $expandedTemplate = $expander->expandUrl($template, ['test' => "losabim"]);

        $this->assertEquals("https://lorem.com/losabim", $expandedTemplate);
    }
}
