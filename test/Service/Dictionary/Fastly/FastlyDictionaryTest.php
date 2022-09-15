<?php

namespace SmartframeTest\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionary;
use PHPUnit\Framework\TestCase;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionaryAdapter;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyItem;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyItems;

class FastlyDictionaryTest extends TestCase
{
    public const FASTLY_SERVICE_NAME = 'test_service';
    public const FASTLY_DICTIONARY_NAME = 'test_dictionary';
    public const TEST_KEY = 'test_key';
    public const TEST_VALUE = 'test_value';

    private FastlyDictionary $fastlyDictionary;

    public function setUp(): void
    {
        parent::setUp();

        $this->fastlyDictionary = $this->createMock(FastlyDictionary::class);
    }

    public function testSyncItemsForService()
    {
        $oldItems = ['abc' => '123'];
        $newItems = ['edf' => '456', 'ghi' => '789'];
        $newFastlyItems = new FastlyItems($newItems);
        $mockedClient = $this->createMock(FastlyDictionaryAdapter::class);
        $mockedClient
            ->expects($this->once())
            ->method('setMultipleKeysValues')
            ->willReturn(true);
        $mockedClient
            ->expects($this->once())
            ->method('deleteMultipleKeys')
            ->willReturn(true);
        $mockedClient
            ->expects($this->once())
            ->method('listKeys')
            ->willReturn($this->itemsGenerator($oldItems));

        $fastlyDictionary = new FastlyDictionary($mockedClient, [self::FASTLY_SERVICE_NAME => 1], [self::FASTLY_SERVICE_NAME => [self::FASTLY_DICTIONARY_NAME => 2]]);

        $result = $fastlyDictionary->syncItemsForService(self::FASTLY_SERVICE_NAME, self::FASTLY_DICTIONARY_NAME, $newFastlyItems);
        $this->assertEquals(3, $result);
    }

    public function testCreateItem()
    {
        $item = FastlyItem::createNew(self::TEST_KEY, self::TEST_VALUE);
        $this->assertEquals(self::TEST_KEY, $item->getKey());
        $this->assertEquals(self::TEST_VALUE, $item->getValue()->getOriginValue());
    }

    private function itemsGenerator(array $items): \Generator
    {
        foreach ($items as $key => $item) {
            yield $key => $item;
        }
    }

}
