<?php
/**
 * Gary Hockin
 * 17/05/2013
 */
namespace FafsClientTest\Controller\Plugin;

use FAFSClient\Controller\Plugin\FafsPlugin;

class FafsPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FafsPlugin
     */
    protected $fafsPlugin;

    public function setUp()
    {
        $this->fafsPlugin = new FafsPlugin(
            $this->getMock(
                'FAFSClient\Service\FAFSClientService',
                array('increment'),
                array(),
                '',
                false
            )
        );
    }

    public function testIncrement()
    {
        $this->assertTrue($this->fafsPlugin->increment('test', 1));
    }
}