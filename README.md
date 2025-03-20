# Utopia Agents

[![Build Status](https://travis-ci.org/utopia-php/agents.svg?branch=master)](https://travis-ci.org/utopia-php/agents)
![Total Downloads](https://img.shields.io/packagist/dt/utopia-php/agents.svg)
[![Discord](https://img.shields.io/discord/564160730845151244?label=discord)](https://appwrite.io/discord)

Utopia Agents is a simple and lite library for building and managing AI agents in PHP applications. This library provides a collection of tools and utilities for creating, managing, and orchestrating AI agents with support for multiple AI providers. This library is maintained by the [Appwrite team](https://appwrite.io).

Although this library is part of the [Utopia Framework](https://github.com/utopia-php/framework) project it is dependency free and can be used as standalone with any other PHP project or framework.

## Getting Started

Install using composer:
```bash
composer require utopia-php/agents
```

## System Requirements

Utopia Framework requires PHP 8.0 or later. We recommend using the latest PHP version whenever possible.

## Features

- **Multiple AI Providers** - Support for OpenAI and Anthropic APIs
- **Flexible Message Types** - Support for text and structured content in messages
- **Conversation Management** - Easy-to-use conversation handling between agents and users
- **Model Selection** - Choose from various AI models (GPT-4, Claude 3, etc.)
- **Parameter Control** - Fine-tune model behavior with temperature and token controls

## Usage

### Basic Example

```php
<?php

use Utopia\Agents\Agent;
use Utopia\Agents\Roles\User;
use Utopia\Agents\Messages\Text;
use Utopia\Agents\Conversation;
use Utopia\Agents\Adapters\OpenAI;

// Create an agent with OpenAI
$adapter = new OpenAI('your-api-key', OpenAI::MODEL_GPT_4_TURBO);
$agent = new Agent($adapter);

// Create a user
$user = new User('user-1', 'John');

// Start a conversation
$conversation = new Conversation($agent);
$conversation
    ->message($user, new Text('What is artificial intelligence?'))
    ->send();
```

### Using Different AI Providers

#### OpenAI

```php
use Utopia\Agents\Adapters\OpenAI;

$openai = new OpenAI(
    apiKey: 'your-api-key',
    model: OpenAI::MODEL_GPT_4_TURBO,
    maxTokens: 2048,
    temperature: 0.7
);
```

Available OpenAI Models:
- `MODEL_GPT_4_TURBO`: Latest GPT-4 Turbo
- `MODEL_GPT_4`: Standard GPT-4
- `MODEL_GPT_3_5_TURBO`: Fast GPT-3.5 Turbo

#### Anthropic

```php
use Utopia\Agents\Adapters\Anthropic;

$anthropic = new Anthropic(
    apiKey: 'your-api-key',
    model: Anthropic::MODEL_CLAUDE_3_SONNET,
    maxTokens: 2048,
    temperature: 0.7
);
```

Available Anthropic Models:
- `MODEL_CLAUDE_3_OPUS`: Most powerful model
- `MODEL_CLAUDE_3_SONNET`: Balanced performance
- `MODEL_CLAUDE_3_HAIKU`: Fast and efficient
- `MODEL_CLAUDE_2_1`: Previous generation

### Managing Conversations

```php
use Utopia\Agents\Roles\User;
use Utopia\Agents\Roles\Assistant;
use Utopia\Agents\Messages\Text;

// Create a conversation with system instructions
// Initialize roles
$user = new User('user-1'); 
$assistant = new Assistant('assistant-1');

$conversation = new Conversation($agent);
$conversation
    ->message($user, new Text('Hello!'))
    ->message($assistant, new Text('Hi! How can I help you today?'))
    ->message($user, new Text('What is the capital of France?'));

// Send and get response
$response = $conversation->send();
```

### Working with Messages

```php
use Utopia\Agents\Messages\Text;
use Utopia\Agents\Messages\Image;

// Text message
$textMessage = new Text('Hello, how are you?');

// Image message
$imageMessage = new Image($imageBinaryContent);
$mimeType = $imageMessage->getMimeType(); // Get the MIME type of the image
```

### Configuring Agents

```php
$agent = new Agent($adapter);
$agent
    ->setDescription('An AI agent specialized in research and analysis')
    ->setCapabilities(['research', 'analysis', 'writing']);
```

## Tests

To run all unit tests, use the following Docker command:

```bash
docker compose exec tests vendor/bin/phpunit --configuration phpunit.xml tests
```

To run static code analysis, use the following Psalm command:

```bash
docker compose exec tests vendor/bin/psalm --show-info=true
```

## Security

We take security seriously. If you discover any security-related issues, please email security@appwrite.io instead of using the issue tracker.

## Contributing

All code contributions - including those of people having commit access - must go through a pull request and be approved by a core developer before being merged. This is to ensure a proper review of all the code.

We truly ❤️ pull requests! If you wish to help, you can learn more about how you can contribute to this project in the [contribution guide](CONTRIBUTING.md).

## Copyright and license

The MIT License (MIT) [http://www.opensource.org/licenses/mit-license.php](http://www.opensource.org/licenses/mit-license.php) 