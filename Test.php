<?php

use PHPUnit\Framework\TestCase;

require_once 'analyze.php';

class Test extends TestCase
{
    public function testOne()
    {
        $expected_result = "14/06/2017:16:48:52 14/06/2017:16:48:52 91.666666666667";
        $anal = new Analyzer();
        $res = $anal->analyze(99.9, 45, true, "test1.log");

        echo "\n----------------------------\n";
        echo $expected_result . "\n";
        echo $res . "\n";
        echo "----------------------------\n";
        $this->assertEquals($expected_result,
            Analyzer::analyze(99.9, 45, true, "test1.log"));
    }

}