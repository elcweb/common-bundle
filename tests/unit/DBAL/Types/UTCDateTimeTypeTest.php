<?php

namespace Elcweb\CommonBundle\tests\unit\DBAL\Types;

use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Elcweb\CommonBundle\DBAL\Types\UTCDateTimeType;

class UTCDateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    private $utcDateTimeType;
    private $platform;

    public function setUp()
    {
        UTCDateTimeType::overrideType('datetime', 'Elcweb\CommonBundle\DBAL\Types\UTCDateTimeType');
        $this->utcDateTimeType = UTCDateTimeType::getType('datetime');

        $this->platform = $this->getMockBuilder(AbstractPlatform::class)->disableOriginalConstructor()->getMock();
        $this->platform
            ->expects($this->any())
            ->method('getDateTimeFormatString')
            ->willReturn('Y-m-d H:i:s');
    }

    /**
     * @param string $expected
     * @param DateTime $value
     *
     * @dataProvider data
     */
    public function testConvertToDatabaseValue($expected, $value)
    {
        $this->assertEquals($expected, $this->utcDateTimeType->convertToDatabaseValue($value, $this->platform));
    }

    /**
     * @param string $value
     * @param DateTime $expected
     *
     * @dataProvider data
     */
    public function testConvertToPHPValue($value, $expected)
    {
        $this->assertEquals($expected, $this->utcDateTimeType->convertToPHPValue($value, $this->platform));
    }

    public function data()
    {
        return [
            [null, null],
            ['1977-04-22 06:00:00', new DateTime('1977-04-22T06:00:00Z')],
            ['1977-04-22 06:00:00', new DateTime('1977-04-22T01:00:00-0500')],
            ['1977-04-22 06:00:00', DateTime::createFromFormat(DateTime::ISO8601, '1977-04-22T01:00:00-0500')],
        ];
    }
}
