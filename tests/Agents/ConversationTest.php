<?php

namespace Tests\Utopia\Agents;

use PHPUnit\Framework\TestCase;
use Utopia\Agents\Adapter;
use Utopia\Agents\Adapters\Anthropic;
use Utopia\Agents\Agent;
use Utopia\Agents\Conversation;
use Utopia\Agents\Messages\Text;
use Utopia\Agents\Role;
use Utopia\Agents\Roles\Assistant;
use Utopia\Agents\Roles\User;

class ConversationTest extends TestCase
{
    private Conversation $conversation;

    private Agent $agent;

    private Adapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new Anthropic(
            getenv('LLM_KEY_ANTHROPIC'),
            Anthropic::MODEL_CLAUDE_3_SONNET,
            1024,
            1.0
        );

        $this->agent = new Agent($this->adapter);
        $this->agent->setDescription('Test Agent Description');

        $this->conversation = new Conversation($this->agent);
    }

    public function testConstructor(): void
    {
        $this->assertSame($this->agent, $this->conversation->getAgent());
        $this->assertEmpty($this->conversation->getMessages());
        $this->assertEquals(0, $this->conversation->getInputTokens());
        $this->assertEquals(0, $this->conversation->getOutputTokens());
        $this->assertEquals(0, $this->conversation->getTotalTokens());
        $this->assertIsCallable($this->conversation->getListener());
    }

    public function testMessage(): void
    {
        $user = new User('user-1', 'Test User');
        $message = new Text('Hello, AI!');

        $result = $this->conversation->message($user, $message);

        $this->assertSame($this->conversation, $result);
        $this->assertCount(1, $this->conversation->getMessages());
        $this->assertEquals([
            'role' => Role::ROLE_USER,
            'content' => 'Hello, AI!',
        ], $this->conversation->getMessages()[0]);
    }

    public function testMultipleMessages(): void
    {
        $user = new User('user-1', 'Test User');
        $assistant = new Assistant('assistant-1', 'Test Assistant');

        $this->conversation
            ->message($user, new Text('Hello'))
            ->message($assistant, new Text('Hi there!'))
            ->message($user, new Text('How are you?'));

        $messages = $this->conversation->getMessages();
        $this->assertCount(3, $messages);

        $this->assertEquals(Role::ROLE_USER, $messages[0]['role']);
        $this->assertEquals('Hello', $messages[0]['content']);

        $this->assertEquals(Role::ROLE_ASSISTANT, $messages[1]['role']);
        $this->assertEquals('Hi there!', $messages[1]['content']);

        $this->assertEquals(Role::ROLE_USER, $messages[2]['role']);
        $this->assertEquals('How are you?', $messages[2]['content']);
    }

    public function testSend(): void
    {
        $messages = $this->conversation
            ->message(new User('user-1', 'Test User'), new Text('Hello'))
            ->send();

        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(Text::class, $messages[0]);

        // Verify the response was added to conversation
        $conversationMessages = $this->conversation->getMessages();
        $this->assertNotEmpty($conversationMessages);
        $this->assertEquals(Role::ROLE_ASSISTANT, $conversationMessages[0]['role']);
    }

    public function testTokenCounting(): void
    {
        $this->conversation->countInputTokens(10);
        $this->assertEquals(10, $this->conversation->getInputTokens());

        $this->conversation->countInputTokens(5);
        $this->assertEquals(15, $this->conversation->getInputTokens());

        $this->conversation->countOutputTokens(20);
        $this->assertEquals(20, $this->conversation->getOutputTokens());

        $this->conversation->countOutputTokens(10);
        $this->assertEquals(30, $this->conversation->getOutputTokens());

        $this->assertEquals(45, $this->conversation->getTotalTokens());
    }

    public function testListener(): void
    {
        $called = false;
        $testListener = function () use (&$called) {
            $called = true;
        };

        $result = $this->conversation->listen($testListener);

        $this->assertSame($this->conversation, $result);
        $this->assertSame($testListener, $this->conversation->getListener());

        // Call the listener to verify it works
        $listener = $this->conversation->getListener();
        $listener();
        $this->assertTrue($called);
    }
}
