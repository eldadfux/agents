<?php

namespace Utopia\Agents;

use Utopia\Agents\Roles\Assistant;

class Conversation
{
    /**
     * @var array<array<string, mixed>>
     */
    protected array $messages = [];

    /**
     * @var Agent
     */
    protected Agent $agent;

    /**
     * @var int
     */
    protected int $inputTokens = 0;

    /**
     * @var int
     */
    protected int $outputTokens = 0;

    /**
     * @var int
     */
    protected int $totalTokens = 0;

    /**
     * @var callable
     */
    protected $listener;

    /**
     * @param  Agent  $agent
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
        $this->listener = function () {
        };
    }

    /**
     * Set a callback to handle chunks
     *
     * @param  callable  $listener
     * @return self
     */
    public function listen(callable $listener): self
    {
        $this->listener = $listener;

        return $this;
    }

    /**
     * Add a message to the conversation
     *
     * @param  Message  $message
     * @param  Role  $from
     * @return self
     */
    public function message(Role $from, Message $message): self
    {
        $this->messages[] = [
            'role' => $from->getIdentifier(),
            'content' => $message->getContent(),
        ];

        return $this;
    }

    /**
     * Send the conversation to the agent and get response
     *
     * @return Message
     *
     * @throws \Exception
     */
    public function send(): Message
    {
        $message = $this->agent->getAdapter()->send($this->messages, $this->listener);
        
        $this->countInputTokens($this->agent->getAdapter()->getInputTokens());
        $this->countOutputTokens($this->agent->getAdapter()->getOutputTokens());

        $from = new Assistant($this->agent->getAdapter()->getModel(), 'Assistant');
        $this->message($from, $message);

        return $message;
    }

    /**
     * Get all messages in the conversation
     *
     * @return array<array<string, mixed>>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Get the agent in the conversation
     *
     * @return Agent
     */
    public function getAgent(): Agent
    {
        return $this->agent;
    }

    /**
     * Get the current listener callback
     *
     * @return callable
     */
    public function getListener(): callable
    {
        return $this->listener;
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
