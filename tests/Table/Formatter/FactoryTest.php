<?php

namespace Tests\Table\Formatter;

use Orbas\Stage\Table\Formatter\Factory;
use Tests\StageTestCase;

class FactoryTest extends StageTestCase
{
    /**
     * @test
     * @group Formatter
     */
    public function it_should_format_from_factory()
    {
        $actual = Factory::format('faCheck', 1);
        $this->assertEquals('<i class="fa fa-check"></i>', $actual);
    }
}