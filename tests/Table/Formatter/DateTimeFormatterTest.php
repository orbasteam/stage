<?php

namespace Tests\Table\Formatter;

use Carbon\Carbon;
use Orbas\Stage\Table\Formatter\DateTime;
use Tests\StageTestCase;

class DateTimeFormatterTest extends StageTestCase
{
    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_normal_dateTime()
    {
        $formatter = new DateTime();
        $value = $formatter->format('2010-07-25 00:00:00', ['Y-m-d']);
        $this->assertEquals('2010-07-25', $value);
    }

    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_carbon_method()
    {
        $formatter = new DateTime();
        $dateTime = new Carbon('2010-07-25 00:00:00');
        $value = $formatter->format($dateTime, ['date']);

        $this->assertEquals('2010-07-25', $value);
    }

    /**
     * @test
     * @group Formatter
     * 
     */
    public function it_should_format_carbon_custom_format()
    {
        $formatter = new DateTime();
        $dateTime = new Carbon('2010-07-25 00:00:00');
        $value = $formatter->format($dateTime, ['Y-m-d']);
        
        $this->assertEquals('2010-07-25', $value);
    }
}