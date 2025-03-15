<?php

namespace Tests\Utopia\Agents\Messages;

use PHPUnit\Framework\TestCase;
use Utopia\Agents\Message;
use Utopia\Agents\Messages\Image;

class ImageTest extends TestCase
{
    private string $pngImageData;

    private string $jpegImageData;

    private string $gifImageData;

    protected function setUp(): void
    {
        // Minimal valid 1x1 PNG image data
        $this->pngImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=');

        // Minimal valid 1x1 JPEG image data
        $this->jpegImageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAH8AAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigD//2Q==');

        // Minimal valid 1x1 GIF image data
        $this->gifImageData = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    }

    public function testConstructor(): void
    {
        $message = new Image($this->pngImageData);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertInstanceOf(Image::class, $message);
    }

    public function testGetContent(): void
    {
        $message = new Image($this->pngImageData);

        $this->assertEquals($this->pngImageData, $message->getContent());
        $this->assertIsString($message->getContent());
    }

    public function testGetMimeTypePNG(): void
    {
        $message = new Image($this->pngImageData);

        $this->assertEquals('image/png', $message->getMimeType());
    }

    public function testGetMimeTypeJPEG(): void
    {
        $message = new Image($this->jpegImageData);

        $this->assertEquals('image/jpeg', $message->getMimeType());
    }

    public function testGetMimeTypeGIF(): void
    {
        $message = new Image($this->gifImageData);

        $this->assertEquals('image/gif', $message->getMimeType());
    }

    public function testEmptyContent(): void
    {
        $message = new Image('');

        $this->assertEquals('', $message->getContent());
        $this->assertNull($message->getMimeType());
    }

    public function testInvalidImageData(): void
    {
        $message = new Image('not an image');

        $this->assertEquals('not an image', $message->getContent());
        $this->assertNotNull($message->getMimeType());
        $this->assertNotEquals('image/png', $message->getMimeType());
        $this->assertNotEquals('image/jpeg', $message->getMimeType());
        $this->assertNotEquals('image/gif', $message->getMimeType());
    }
}
