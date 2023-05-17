<?php

namespace TextMod;

use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use function \json_last_error;
use function \json_last_error_msg;

class TextMod
{
    const BASE_URL = 'https://api.textmod.xyz';

    const SPAM = 'spam';
    const SELF_PROMOTING = 'self-promoting';
    const HATE = 'hate';
    const TERRORISM = 'terrorism';
    const EXTREMISM = 'extremism';
    const PORNOGRAPHIC = 'pornographic';
    const THREATENING = 'threatening';
    const SELF_HARM = 'self-harm';
    const SEXUAL = 'sexual';
    const SEXUAL_MINORS = 'sexual/minors';
    const VIOLENCE = 'violence';
    const VIOLENCE_GRAPHIC = 'violence/graphic';

    private $authToken;
    public $allowedSentiments;

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
            'body' => json_encode($requestBody),
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse JSON response: ' . json_last_error_msg());
        }

        return new ModerationResult($responseData);
    }

    private function getSentimentsParameters(array $sentiments): string
    {
        $allowedSentiments = array_filter($sentiments, function ($sentiment) {
            return in_array($sentiment, [
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
            ]);
        });

        $kebabCaseSentiments = array_map(function ($sentiment) {
            return CaseConverter::toKebabCase($sentiment);
        }, $allowedSentiments);

        return count($kebabCaseSentiments) > 0
            ? '?' . http_build_query(array_map(function ($sentiment) {
                return ['sentiments[]' => $sentiment];
            }, $kebabCaseSentiments))
            : '';
    }
}
