<?php

namespace Utopia\Agents;

abstract class Adapter
{
    /**
     * The agent instance
     *
     * @var ?Agent
     */
    protected ?Agent $agent = null;

    /**
     * Input tokens count
     *
     * @var int
     */
    protected int $inputTokens = 0;

    /**
     * Output tokens count
     *
     * @var int
     */
    protected int $outputTokens = 0;

    /**
     * Get the adapter name
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Send a message to the AI model
     *
     * @param  array<Message>  $messages The messages to send to the AI model
     * @param  callable|null  $listener The listener to call when the message is sent
     * @return Message Response from the AI model
     *
     * @throws \Exception
     */
    abstract public function send(array $messages, ?callable $listener = null): Message;

    /**
     * Get available models for this adapter
     *
     * @return array<string>
     */
    abstract public function getModels(): array;

    /**
     * Get the currently selected model
     *
     * @return string
     */
    abstract public function getModel(): string;

    /**
     * Set the model to use
     *
     * @param  string  $model
     * @return self
     *
     * @throws \Exception if model is not supported
     */
    abstract public function setModel(string $model): self;

    /**
     * Get the current agent
     *
     * @return ?Agent
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * Set the agent
     *
     * @param  Agent  $agent
     * @return self
     */
    public function setAgent(Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }


    /**
     * Get input tokens count
     *
     * @return int
     */
    public function getInputTokens(): int
    {
        return $this->inputTokens;
    }

    /**
     * Add to input tokens count
     *
     * @param  int  $tokens
     * @return self
     */
    public function countInputTokens(int $tokens): self
    {
        $this->inputTokens += $tokens;

        return $this;
    }

    /**
     * Get output tokens count
     *
     * @return int
     */
    public function getOutputTokens(): int
    {
        return $this->outputTokens;
    }

    /**
     * Add to output tokens count
     *
     * @param  int  $tokens
     * @return self
     */
    public function countOutputTokens(int $tokens): self
    {
        $this->outputTokens += $tokens;

        return $this;
    }

    /**
     * Get total tokens count
     *
     * @return int
     */
    public function getTotalTokens(): int
    {
        return $this->inputTokens + $this->outputTokens;
    }
}
