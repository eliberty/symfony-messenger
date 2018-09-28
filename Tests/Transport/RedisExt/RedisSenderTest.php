<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Messenger\Tests\Transport\RedisExt;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Tests\Fixtures\DummyMessage;
use Symfony\Component\Messenger\Transport\RedisExt\Connection;
use Symfony\Component\Messenger\Transport\RedisExt\RedisSender;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

/**
 * @requires extension redis
 */
class RedisSenderTest extends TestCase
{
    public function testItSendsTheEncodedMessage()
    {
        $envelope = Envelope::wrap(new DummyMessage('Oy'));
        $encoded = array('body' => '...', 'headers' => array('type' => DummyMessage::class));

        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();
        $serializer->method('encode')->with($envelope)->willReturnOnConsecutiveCalls($encoded);

        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection->expects($this->once())->method('add')->with($encoded);

        $sender = new RedisSender($connection, $serializer);
        $sender->send($envelope);
    }
}
