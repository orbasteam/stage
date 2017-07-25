<?php

namespace Tests\Table\Formatter;

use Orbas\Stage\Table\Formatter\Mailto;
use Tests\StageTestCase;

class MailtoTest extends StageTestCase
{
    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_mailto_html()
    {
        $formatter = new Mailto();
        $email = 'flash662@gmail.com';
        $expected = '<a href="mailto:' . $email . '">' . $email . '</a>'; 
        
        $this->assertEquals($formatter->format($email), $expected);
    }
}