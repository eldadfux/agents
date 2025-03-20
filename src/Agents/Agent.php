<?php

namespace Utopia\Agents;

class Agent
{
    /**
     * @var string
     */
    protected string $description;

    /**
     * @var array<string, string>
     */
    protected array $instructions;

    /**
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Create a new agent
     *
     * @param  Adapter  $adapter The AI model adapter to use
     *
     * @throws \Exception
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->instructions = [];
        $this->description = '';

        $this->adapter->setAgent($this);
    }

    /**
     * Set the agent's description
     *
     * @param  string  $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the agent's instructions
     *
     * @param  array<string, string>  $instructions
     * @return self
     */
    public function setInstructions(array $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Add an instruction to the agent
     *
     * @param  string  $name
     * @param  string  $content
     * @return self
     */
    public function addInstruction(string $name, string $content): self
    {
        $this->instructions[$name] = $content;

        return $this;
    }

    /**
     * Get the agent's description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the agent's instructions
     *
     * @return array<string, string>
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }

    /**
     * Get the agent's adapter
     *
     * @return Adapter
     */
    public function getAdapter(): Adapter
    {
        return $this->adapter;
    }
}
