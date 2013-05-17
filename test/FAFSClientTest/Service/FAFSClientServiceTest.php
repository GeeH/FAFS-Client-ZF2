<?php
/**
 * Gary Hockin
 * 17/05/2013
 */
namespace FafsClientTest\Service;

use FAFSClient\Service\FAFSClientService;

class FAFSClientServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FAFSClientService
     */
    protected $FAFSClientService;

    public function setUp()
    {
        $this->FAFSClientService = new FAFSClientService(array(
            'ipAddress' => '123.123.123.123',
            'port' => '6543'
        ));
    }

    public function testIncrement()
    {
        $this->FAFSClientService->increment('test');
        $counter = $this->FAFSClientService->getKey('test');
        $this->assertEquals(1, $counter[2]);

        $this->FAFSClientService->increment('test');
        $counter = $this->FAFSClientService->getKey('test');
        $this->assertEquals(2, $counter[2]);

        $this->FAFSClientService->increment('test', 10);
        $counter = $this->FAFSClientService->getKey('test');
        $this->assertEquals(12, $counter[2]);

        $this->FAFSClientService->increment('another.test', 222);
        $counter = $this->FAFSClientService->getKey('another.test');
        $this->assertEquals(222, $counter[2]);

        $this->assertFalse($this->FAFSClientService->getKey('non.existant.key'));
    }

    public function testDumpLog()
    {
        $this->assertTrue($this->FAFSClientService->dumpLog());
    }
}