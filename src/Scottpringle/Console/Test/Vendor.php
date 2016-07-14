<?php

namespace Scottpringle\Console\Test;

use Scottpringle\Console\Model\Vendor;

/**
 * Some unit tests for the Vendor class
 *
 * Class VendorTest
 * @package Scottpringle\Console\Test
 */
class VendorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the convertDateNumber method
     */
    public function testConvertDateToNumber()
    {
        $vendor = new Vendor();

        $date = '2015-12-27';
        $day = $vendor->convertDateToNumber($date);

        // day is a Sunday which means its a 7
        $this->assertEquals($day, 7);

        $date = '2015-12-25';
        $day = $vendor->convertDateToNumber($date);

        // day is a Friday which means its a 5
        $this->assertEquals($day, 5);
    }

    /**
     * Tests the convertTimes method
     */
    public function testConvertTimes()
    {
        $vendor = new Vendor();

        $row = array(
            'event_type' => 'opened',
            'all_day' => "0",
            'start_hour' => '19:00:00',
            'stop_hour' => '22:00:00',
        );

        $converted = $vendor->convertTimes($row);

        $this->assertEquals($converted, array(
            array(
                'all_day' => 0,
                'start_hour' => '19:00:00',
                'stop_hour' => '22:00:00',
            )
        ));

        $row = array(
            'event_type' => 'closed',
            'all_day' => "1",
            'start_hour' => null,
            'stop_hour' => null,
        );

        $converted = $vendor->convertTimes($row);

        $this->assertEquals($converted, array(
            array(
            )
        ));

        $row = array(
            'event_type' => 'opened',
            'all_day' => "1",
            'start_hour' => null,
            'stop_hour' => null,
        );

        $converted = $vendor->convertTimes($row);

        $this->assertEquals($converted, array(
            array(
                'all_day' => 1,
                'start_hour' => null,
                'stop_hour' => null,
            )
        ));

        $row = array(
            'event_type' => 'closed',
            'all_day' => "0",
            'start_hour' => "14:00:00",
            'stop_hour' => "16:00:00",
        );

        $converted = $vendor->convertTimes($row);

        $this->assertEquals($converted, array(
            array(
                'all_day' => 0,
                'start_hour' => "00:00:00",
                'stop_hour' => "14:00:00",
            ),
            array(
                'all_day' => 0,
                'start_hour' => "16:00:00",
                'stop_hour' => "23:59:59",
            )
        ));
    }
}