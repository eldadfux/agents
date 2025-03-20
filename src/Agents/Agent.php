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
    protected array $capabilities;

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
        $this->capabilities = [];
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
     * Set the agent's capabilities
     *
     * @param  array<string, string>  $capabilities
     * @return self
     */
    public function setCapabilities(array $capabilities): self
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * Add a capability to the agent
     *
     * @param  string  $name
     * @param  string  $content
     * @return self
     */
    public function addCapability(string $name, string $content): self
    {
        $this->capabilities[$name] = $content;

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
     * Get the agent's capabilities
     *
     * @return array<string, string>
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
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
