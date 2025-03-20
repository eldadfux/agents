<?php

namespace Utopia\Agents\Adapters;

use Utopia\Agents\Adapter;
use Utopia\Agents\Conversation;
use Utopia\Agents\Message;
use Utopia\Agents\Messages\Text;
use Utopia\Agents\Roles\Assistant;

class OpenAI extends Adapter
{
    /**
     * GPT-4 Turbo - Latest and most capable model
     */
    public const MODEL_GPT_4_TURBO = 'gpt-4-turbo-preview';

    /**
     * GPT-4 - Previous generation model
     */
    public const MODEL_GPT_4 = 'gpt-4';

    /**
     * GPT-3.5 Turbo - Fast and efficient model
     */
    public const MODEL_GPT_3_5_TURBO = 'gpt-3.5-turbo';

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
     * Create a new OpenAI adapter
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
        string $model = self::MODEL_GPT_3_5_TURBO,
        int $maxTokens = 1024,
        float $temperature = 1.0
    ) {
        $this->apiKey = $apiKey;
        $this->maxTokens = $maxTokens;
        $this->temperature = $temperature;
        $this->setModel($model);
    }

    /**
     * Send a message to the OpenAI API
     *
     * @param  Conversation  $conversation
     * @return Message Response from the AI model
     *
     * @throws \Exception
     */
    public function send(Conversation $conversation): Message
    {
        // TODO: Implement OpenAI API call
        // Example implementation structure:
        // $response = [make API call with $conversation->getMessages()];
        // $conversation->setInputTokens($response['usage']['prompt_tokens']);
        // $conversation->setOutputTokens($response['usage']['completion_tokens']);
        // $message = new Text($response['choices'][0]['message']['content']);
        // $conversation->message(new Assistant('openai'), $message);
        // return $message;
        throw new \Exception('Not implemented');
    }

    /**
     * Get available models
     *
     * @return array<string>
     */
    public function getModels(): array
    {
        return [
            self::MODEL_GPT_4_TURBO,
            self::MODEL_GPT_4,
            self::MODEL_GPT_3_5_TURBO,
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
        return 'openai';
    }
}
