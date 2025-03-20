<?php

namespace Utopia\Agents;

abstract class Message
{
    /**
     * @var string
     */
    protected string $content;

    /**
     * Create a new message
     *
     * @param  string  $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Get the message content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
