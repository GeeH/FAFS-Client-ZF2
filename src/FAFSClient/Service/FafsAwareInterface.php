<?php
/**
 * Gary Hockin
 * 25/04/2013
 */

namespace FAFSClient\Service;

interface FafsAwareInterface
{
    /**
     * Set FAFS Service
     *
     * @param FAFSClientService $fafsService
     */
    public function setFafs(FAFSClientService $fafsService);
}