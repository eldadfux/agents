<?php

namespace Utopia\Agents\Messages;

use Utopia\Agents\Message;

class Text extends Message
{
    /**
     * Create a new text message
     *
     * @param  string  $content
     */
    public function __construct(string $content)
    {
        parent::__construct($content);
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
