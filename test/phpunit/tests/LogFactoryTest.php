<?php
/**
 * @link https://github.com/old-town/old-town-propertyset
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\PropertySet\Test;

use PHPUnit_Framework_TestCase as TestCase;
use \OldTown\Log\LogFactory;
use \Psr\Log\NullLogger;


/**
 * Class PropertySetManagerTest
 *
 * @package OldTown\PropertySet\Test
 */
class LogFactoryTest extends TestCase
{
    /**
     * Создание логера
     *
     * @return void
     */
    public function testCreatePropertySetMemory()
    {
        $log = LogFactory::getLog();

        static::assertInstanceOf(NullLogger::class, $log);
    }
}
