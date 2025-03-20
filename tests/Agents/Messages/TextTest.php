<?php

namespace Tests\Utopia\Agents\Messages;

use PHPUnit\Framework\TestCase;
use Utopia\Agents\Message;
use Utopia\Agents\Messages\Text;

class TextTest extends TestCase
{
    public function testConstructor(): void
    {
        $content = 'Hello, world!';
        $message = new Text($content);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertInstanceOf(Text::class, $message);
    }

    public function testGetContent(): void
    {
        $content = 'Test message content';
        $message = new Text($content);

        $this->assertEquals($content, $message->getContent());
        $this->assertIsString($message->getContent());
    }

    public function testEmptyContent(): void
    {
        $message = new Text('');

        $this->assertEquals('', $message->getContent());
        $this->assertIsString($message->getContent());
    }

    public function testMultilineContent(): void
    {
        $content = "Line 1\nLine 2\nLine 3";
        $message = new Text($content);

        $this->assertEquals($content, $message->getContent());
        $this->assertStringContainsString("\n", $message->getContent());
    }

    public function testSpecialCharacters(): void
    {
        $content = 'Special chars: !@#$%^&*()_+ 😀 🌟';
        $message = new Text($content);

        $this->assertEquals($content, $message->getContent());
    }
}
