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
     * Get the adapter name
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Send a message to the AI model
     *
     * @param  Conversation  $conversation The conversation instance containing messages and tracking tokens
     * @return array<Message> Response from the AI model
     *
     * @throws \Exception
     */
    abstract public function send(Conversation $conversation): array;

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
}
