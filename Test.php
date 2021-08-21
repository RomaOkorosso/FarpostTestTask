<?php

use PHPUnit\Framework\TestCase;

require_once 'analyze.php';

class Test extends TestCase
{
    public function testLargeData()
    {
        $expected_result = "14/06/2017:16:48:51 14/06/2017:16:49:02 91.666666666667";
        $res = Analyzer::analyze(99.9, 45, true, "test1.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }

    public function testThreeWrongWithTwoNormal()
    {
        $expected_result = "14/06/2017:16:48:52 14/06/2017:16:48:52 014/06/2017:16:48:54 14/06/2017:16:48:56 33.333333333333";
        $res = Analyzer::analyze(99.9, 45, true, "test2.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }

    public function testOnly500ResCodes()
    {
        $expected_result = "14/06/2017:16:48:52 14/06/2017:16:48:56 0";
        $res = Analyzer::analyze(99.9, 45, true, "test3.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }

    public function testMixedAllWrong()
    {
        $expected_result = "14/06/2017:16:48:52 14/06/2017:16:48:56 0";
        $res = Analyzer::analyze(99.9, 45, true, "test4.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }

    public function testMixed()
    {
        $expected_result = '14/06/2017:16:48:52 14/06/2017:16:48:57 5014/06/2017:16:48:59 14/06/2017:16:49:00 50';
        $res = Analyzer::analyze(99.9, 45, true, "test5.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }

    public function testWrongInMiddle()
    {
        $expected_result = "14/06/2017:16:48:52 14/06/2017:16:48:55 50";
        $res = Analyzer::analyze(99.9, 45, true, "test6.log");
        $res = str_replace("\n", "", $res);
        $this->assertEquals($expected_result, $res);
    }
}

