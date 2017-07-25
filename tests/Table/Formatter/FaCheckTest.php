<?php
namespace Tests\Table\Formatter;

use Orbas\Stage\Table\Formatter\FaCheck;
use Tests\StageTestCase;

class FaCheckTest extends StageTestCase
{
    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_true()
    {
        $formatter = new FaCheck();
        $expected = '<i class="fa fa-check"></i>';
        $this->assertEquals($formatter->format(1), $expected);
        $this->assertEquals($formatter->format(true), $expected);
    }

    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_false()
    {
        $formatter = new FaCheck();
        $expected  = '';
        
        $this->assertEquals($formatter->format(0), $expected);
        $this->assertEquals($formatter->format(false), $expected);
    }
}