<?php

namespace TextMod\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use TextMod\TextMod;
use TextMod\ModerationResult;

/**
 * @package TextMod\Test
 */
class TextModTest extends TestCase
{
    private string $authToken;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->authToken = getenv('TEXTMOD_AUTH_TOKEN');

        if (!$this->authToken) {
            throw new Exception('No TextMod auth token found in environment variables');
        }

        parent::setUp();
    }

    public function testModerateMethod(): void
    {
        $textMod = new TextMod(['authToken' => $this->authToken]);
        $text = 'This is some test text';
        $moderationResult = $textMod->moderate($text);

        $this->assertInstanceOf(ModerationResult::class, $moderationResult);
        $this->assertFalse($moderationResult->spam);
        $this->assertFalse($moderationResult->selfPromoting);
        $this->assertFalse($moderationResult->hate);
        $this->assertFalse($moderationResult->terrorism);
        $this->assertFalse($moderationResult->extremism);
        $this->assertFalse($moderationResult->pornographic);
        $this->assertFalse($moderationResult->threatening);
        $this->assertFalse($moderationResult->selfHarm);
        $this->assertFalse($moderationResult->sexual);
        $this->assertFalse($moderationResult->sexualMinors);
        $this->assertFalse($moderationResult->violence);
        $this->assertFalse($moderationResult->violenceGraphic);
    }

    public function testModerateMethodWithCustomFilterSentiments(): void
    {
        $text = 'This is some test text';
        $filterSentiments = [
            TextMod::SPAM,
            TextMod::SELF_PROMOTING,
            TextMod::HATE,
            TextMod::TERRORISM,
            TextMod::EXTREMISM,
            TextMod::PORNOGRAPHIC,
            TextMod::THREATENING,
            TextMod::SELF_HARM,
            TextMod::SEXUAL,
            TextMod::SEXUAL_MINORS,
            TextMod::VIOLENCE,
            TextMod::VIOLENCE_GRAPHIC,
        ];
        $textModWithFilter = new TextMod(['authToken' => $this->authToken, 'filterSentiments' => $filterSentiments]);
        $moderationResult = $textModWithFilter->moderate($text);

        $this->assertInstanceOf(ModerationResult::class, $moderationResult);
        $this->assertFalse($moderationResult->spam);
        $this->assertFalse($moderationResult->selfPromoting);
        $this->assertFalse($moderationResult->hate);
        $this->assertFalse($moderationResult->terrorism);
        $this->assertFalse($moderationResult->extremism);
        $this->assertFalse($moderationResult->pornographic);
        $this->assertFalse($moderationResult->threatening);
        $this->assertFalse($moderationResult->selfHarm);
        $this->assertFalse($moderationResult->sexual);
        $this->assertFalse($moderationResult->sexualMinors);
        $this->assertFalse($moderationResult->violence);
        $this->assertFalse($moderationResult->violenceGraphic);
    }
}
