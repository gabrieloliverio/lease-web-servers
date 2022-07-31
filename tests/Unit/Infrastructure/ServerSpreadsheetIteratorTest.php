<?php


declare(strict_types=1);

use App\Domain\Server;
use App\Infrastructure\ServerSpreadsheetIterator;
use PHPUnit\Framework\TestCase;

class ServerSpreadsheetIteratorTest extends TestCase
{
    private $iterator;

    public function setUp(): void
    {
        $this->iterator = new ServerSpreadsheetIterator("assets/LeaseWeb_servers_filters_assignment.xlsx");
    }

    public function testHasAllExpectedItems(): void
    {
        $count = 0;
        $servers = [];
        foreach ($this->iterator as $server) {
            $servers[] = $server;
            $count++;
        }

        $this->assertEquals(486, $count);
        $this->assertContainsOnlyInstancesOf(Server::class, $servers);
    }

    public function testCurrentFirst()
    {
        $this->assertInstanceOf(Server::class, $this->iterator->current());
    }

    public function testRewind()
    {
        while ($this->iterator->next()) {
        }

        $this->iterator->rewind();
        $this->assertEquals(0, $this->iterator->key());
    }
}
