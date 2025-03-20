<?php

namespace Utopia\Agents\Adapters;

use Utopia\Agents\Adapter;
use Utopia\Agents\Conversation;
use Utopia\Agents\Message;
use Utopia\Agents\Messages\Text;
use Utopia\Fetch\Chunk;
use Utopia\Fetch\Client;

class Anthropic extends Adapter
{
    /**
     * Claude 3 Opus - Most powerful model for highly complex tasks
     */
    public const MODEL_CLAUDE_3_OPUS = 'claude-3-opus-20240229';

    /**
     * Claude 3 Sonnet - Ideal balance of intelligence and speed
     */
    public const MODEL_CLAUDE_3_SONNET = 'claude-3-7-sonnet-20250219';

    /**
     * Claude 3 Haiku - Fastest and most compact model
     */
    public const MODEL_CLAUDE_3_HAIKU = 'claude-3-haiku-20240229';

    /**
     * Claude 2.1 - Previous generation model
     */
    public const MODEL_CLAUDE_2_1 = 'claude-2.1';

    /**
     * @var string
     */
    protected string $apiKey;

    /**
     * @var string
     */
    protected string $model;

    /**
     * @var int
     */
    protected int $maxTokens;

    /**
     * @var float
     */
    protected float $temperature;

    /**
     * Create a new Anthropic adapter
     *
     * @param  string  $apiKey
     * @param  string  $model
     * @param  int  $maxTokens
     * @param  float  $temperature
     *
     * @throws \Exception
     */
    public function __construct(
        string $apiKey,
        string $model = self::MODEL_CLAUDE_3_SONNET,
        int $maxTokens = 1024,
        float $temperature = 1.0
    ) {
        $this->apiKey = $apiKey;
        $this->maxTokens = $maxTokens;
        $this->temperature = $temperature;
        $this->setModel($model);
    }

    /**
     * Send a message to the Anthropic API
     *
     * @param  array<Message>  $messages
     * @param  callable|null  $listener
     * @return Message
     *
     * @throws \Exception
     */
    public function send(array $messages, ?callable $listener = null): Message
    {
        if ($this->getAgent() === null) {
            throw new \Exception('Agent not set');
        }

        $client = new \Utopia\Fetch\Client();
        $client
            ->setTimeout(90)
            ->addHeader('x-api-key', $this->apiKey)
            ->addHeader('anthropic-version', '2023-06-01')
            ->addHeader('content-type', 'application/json');

        foreach ($messages as $message) {
            $messages[] = [
                'role' => $message['role'],
                'content' => $message['content'],
            ];
        }

        $instructions = [];
        foreach ($this->getAgent()->getInstructions() as $name => $content) {
            $instructions[] = "# " . $name . "\n\n" . $content;
        }

        $content = '';
        $response = $client->fetch(
            'https://api.anthropic.com/v1/messages',
            Client::METHOD_POST,
            [
                'model' => $this->model,
                'system' => $this->getAgent()->getDescription() . 
                    (empty($instructions) ? '' : "\n\n" . implode("\n\n", $instructions)),
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'stream' => true,
            ],
            [],
            function ($chunk) use (&$content, $listener) {
                $content .= $this->process($chunk, $listener);
            }
        );

        if ($response->getStatusCode() >= 400) {
            throw new \Exception('Anthropic API error ('.$response->getStatusCode().'): '.$response->getBody());
        }

        $message = new Text($content);

        return $message;
    }

    /**
     * Process a stream chunk from the Anthropic API
     *
     * @param  \Utopia\Fetch\Chunk  $chunk
     * @param  Conversation  $conversation
     * @param  callable|null  $listener
     * @return string
     *
     * @throws \Exception
     */
    protected function process(Chunk $chunk, ?callable $listener): string
    {
        $block = '';
        $data = $chunk->getData();
        $lines = explode("\n", $data);

        foreach ($lines as $line) {

            if (empty(trim($line))) {
                continue;
            }

            if (! str_starts_with($line, 'data: ')) {
                continue;
            }

            $json = json_decode(substr($line, 6), true);
            if (! is_array($json)) {
                continue;
            }

            $type = $json['type'] ?? null;
            if ($type === null) {
                continue;
            }

            switch ($type) {
                case 'message_start':
                    if (isset($json['message']['usage'])) {
                        $usage = $json['message']['usage'];
                        if (isset($usage['input_tokens']) && is_int($usage['input_tokens'])) {
                            $this->countInputTokens($usage['input_tokens']);
                        }
                        if (isset($usage['output_tokens']) && is_int($usage['output_tokens'])) {
                            $this->countOutputTokens($usage['output_tokens']);
                        }
                    }
                    break;

                case 'content_block_start':
                    // Initialize content block
                    break;

                case 'content_block_delta':
                    if (! isset($json['delta']['type'])) {
                        break;
                    }

                    $deltaType = $json['delta']['type'];

                    if ($deltaType === 'text_delta' && isset($json['delta']['text']) && is_string($json['delta']['text'])) {
                        $block = $json['delta']['text'];
                    }

                    if (!empty($block)) {
                        if ($listener !== null) {
                            $listener($block);
                        }
                    }
                    break;

                case 'content_block_stop':
                    // End of content block
                    break;

                case 'message_delta':
                    if (isset($json['usage'])) {
                        $usage = $json['usage'];
                        if (isset($usage['input_tokens']) && is_int($usage['input_tokens'])) {
                            $this->countInputTokens($usage['input_tokens']);
                        }
                        if (isset($usage['output_tokens']) && is_int($usage['output_tokens'])) {
                            $this->countOutputTokens($usage['output_tokens']);
                        }
                    }
                    break;

                case 'message_stop':
                    break;

                case 'error':
                    $errorMessage = isset($json['error']['message']) ? (string) $json['error']['message'] : 'Unknown error';
                    throw new \Exception('Anthropic API error: '.$errorMessage);
            }
        }

        return $block;
    }

    /**
     * Get available models
     *
     * @return array<string>
     */
    public function getModels(): array
    {
        return [
            self::MODEL_CLAUDE_3_OPUS,
            self::MODEL_CLAUDE_3_SONNET,
            self::MODEL_CLAUDE_3_HAIKU,
            self::MODEL_CLAUDE_2_1,
        ];
    }

    /**
     * Get current model
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Set model to use
     *
     * @param  string  $model
     * @return self
     *
     * @throws \Exception
     */
    public function setModel(string $model): self
    {
        if (! in_array($model, $this->getModels())) {
            throw new \Exception('Unsupported model: '.$model);
        }

        $this->model = $model;

        return $this;
    }

    /**
     * Set max tokens
     *
     * @param  int  $maxTokens
     * @return self
     */
    public function setMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    /**
     * Set temperature
     *
     * @param  float  $temperature
     * @return self
     */
    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get the adapter name
     *
     * @return string
     */
    public function getName(): string
    {
        return 'anthropic';
    }
}
