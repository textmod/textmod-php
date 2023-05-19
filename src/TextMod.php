<?php

namespace TextMod;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use function json_last_error;
use function json_last_error_msg;

class TextMod
{
    public const BASE_URL = 'https://api.textmod.xyz';

    public const SPAM = 'spam';
    public const SELF_PROMOTING = 'self-promoting';
    public const HATE = 'hate';
    public const TERRORISM = 'terrorism';
    public const EXTREMISM = 'extremism';
    public const PORNOGRAPHIC = 'pornographic';
    public const THREATENING = 'threatening';
    public const SELF_HARM = 'self-harm';
    public const SEXUAL = 'sexual';
    public const SEXUAL_MINORS = 'sexual/minors';
    public const VIOLENCE = 'violence';
    public const VIOLENCE_GRAPHIC = 'violence/graphic';

    private string $authToken;
    public array $allowedSentiments;

    public function __construct(array $config)
    {
        $this->authToken = $config['authToken'];
        $this->allowedSentiments = $config['filterSentiments'] ?? [
            self::SPAM,
            self::SELF_PROMOTING,
            self::HATE,
            self::TERRORISM,
            self::EXTREMISM,
            self::SELF_HARM,
        ];
    }

    /**
     * @throws RuntimeException|GuzzleException
     */
    public function moderate(string $text): ModerationResult
    {
        $sentiments = $this->getSentimentsParameters($this->allowedSentiments);
        $requestBody = ['content' => $text];

        $client = new Client();
        $response = $client->post(self::BASE_URL . '/api/text/mod' . $sentiments, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->authToken,
            ],
            'body' => Utils::jsonEncode($requestBody),
        ]);

        $responseData = Utils::jsonDecode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to parse JSON response: ' . json_last_error_msg());
        }

        return new ModerationResult($responseData);
    }

    private function getSentimentsParameters(array $sentiments): string
    {
        $allowedSentiments = array_filter($sentiments, fn($sentiment) => in_array($sentiment, [
            self::SPAM,
            self::SELF_PROMOTING,
            self::HATE,
            self::TERRORISM,
            self::EXTREMISM,
            self::PORNOGRAPHIC,
            self::THREATENING,
            self::SELF_HARM,
            self::SEXUAL,
            self::SEXUAL_MINORS,
            self::VIOLENCE,
            self::VIOLENCE_GRAPHIC,
        ]));

        $kebabCaseSentiments = array_map(fn($sentiment) => CaseConverter::toKebabCase($sentiment), $allowedSentiments);

        return count($kebabCaseSentiments) > 0
            ? '?' . http_build_query(array_map(fn($sentiment) => ['sentiments[]' => $sentiment], $kebabCaseSentiments))
            : '';
    }
}
