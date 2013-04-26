<?php
/**
 * Gary Hockin
 * 25/04/2013
 */

namespace FAFSClient\Service;

class FAFSClientService
{
    protected $config;
    protected $counters = array();
    protected $timestamp;

    public function __construct()
    {
        $this->timestamp = time();
    }

    /**
     * @return bool
     */
    public function dumpLog()
    {
        $server_ip = '127.0.0.1';
        $server_port = 6610;
        if ($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
            socket_sendto($socket, json_encode($this->counters), 1024, 0, $server_ip, $server_port);
        }
        return true;
    }

    /**
     * @param $key
     * @param int $value
     * @return bool
     */
    public function increment($key, $value = 1)
    {
        foreach ($this->counters as $c => $counter) {
            if ($counter[1] === $key) {
                $this->counters[$c][2] += $value;
                return true;
            }
        }
        $this->counters[] = array($this->timestamp, $key, (int)$value);
        return true;
    }


}