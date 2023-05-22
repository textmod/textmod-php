# TextMod SDK for PHP

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![codecov](https://codecov.io/gh/textmod/textmod-php/branch/php8/graph/badge.svg?token=JCi7zaNRHv)](https://codecov.io/gh/textmod/textmod-php)

This is an SDK for the [TextMod API](https://textmod.xyz/) that allows you to easily moderate text content for various sentiments such as spam, hate, and pornography.

## Installation

To use the TextMod SDK for PHP, you can add it as a dependency in your `composer.json` file:

```json
{
    "require": {
        "textmod/textmod-php": "^8.0"
    }
}
```

Then, run `composer install` to install the SDK.

## Usage

To use the TextMod SDK, you'll need an API authentication token from the [TextMod website](https://textmod.xyz).
Once you have that, you can create an instance of the `TextMod` class:

```php
<?php

use TextMod\TextMod;

$textmod = new TextMod([
    'authToken' => '<YOUR_AUTH_TOKEN>',
    'filterSentiments' => [
        TextMod::SPAM,
        TextMod::SELF_PROMOTING,
        TextMod::HATE,
        TextMod::TERRORISM,
        TextMod::EXTREMISM,
        TextMod::SELF_HARM
    ]
]);
```

The `filterSentiments` option is optional and defaults to allowing all sentiments.
If specified, only the specified sentiments will be moderated.

You can then use the moderate method to `moderate` text content:

```php
<?php

$text = 'Hello world!';
$result = $textmod->moderate($text);
var_dump($result);

```

The `moderate` method returns a `ModerationResult` object.
You can access the moderated sentiments as properties of the `ModerationResult` object.

## API Documentation

### TextMod

The `TextMod` class is the main class in this SDK. It has one method:

```php
moderate(string $text): ModerationResult
```

Moderates the specified text and returns a `ModerationResult` object.
The `$text` parameter is a string representing the content to moderate.

### ModerationResult

The `ModerationResult` class represents the result returned by the `moderate` method.
It has boolean properties for each sentiment that has been moderated.

## Example

Here's an example of how to use the `TextMod` class:

```php
<?php

use TextMod\TextMod;

$textmod = new TextMod([
    'authToken' => '<YOUR_AUTH_TOKEN>',
    'filterSentiments' => [
        TextMod::SPAM,
        TextMod::SELF_PROMOTING,
        TextMod::HATE,
        TextMod::TERRORISM,
        TextMod::EXTREMISM,
        TextMod::SELF_HARM
    ]
]);

$text = 'Hello world!';
$result = $textmod->moderate($text);
// var_dump($result);
```

## Contributing

If you have suggestions for how this SDK could be improved, or want to report a bug, please open an issue! We welcome contributions from the community.

## License

This SDK is released under the [MIT License](./LICENSE.md).