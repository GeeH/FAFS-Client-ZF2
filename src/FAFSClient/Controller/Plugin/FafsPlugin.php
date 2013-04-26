<?php
/**
 * Gary Hockin
 * 25/04/2013
 */

namespace FAFSClient\Controller\Plugin;

use FAFSClient\Service\FAFSClientService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FafsPlugin extends AbstractPlugin
{
    /**
     * @var FAFSClientService
     */
    protected $fafsService;

    /**
     * @param FAFSClientService $fafsService
     */
    public function __construct(FAFSClientService $fafsService)
    {
        $this->fafsService = $fafsService;
    }

    /**
     * @param $key
     * @param int $count
     * @return bool
     */
    public function increment($key, $count = 1)
    {
        $this->fafsService->increment($key, $count);
        return true;
    }


}