<?php
namespace FAFSClient;

use FAFSClient\Controller\Plugin\FafsPlugin;
use FAFSClient\Exception\RuntimeException;
use FAFSClient\Service\FAFSClientService;
use FAFSClient\Service\FafsAwareInterface;
use Zend\EventManager\Event;
use Zend\Mvc\Application;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        /** @var Application $application  */
        $application = $e->getTarget();
        $eventManager = $application->getEventManager();
        $services = $application->getServiceManager();

        /**
         * Bind event to application finish to dump stats to UDP server
         */
        $eventManager->attach('finish', function($services) use ($services) {
            /** @var FAFSClientService $service  */
            $service = $services->get('FAFSClient\Service\FAFSClientService');
            $service->dumpLog();
        });
        /**
         * Allow fafs key to be incremented by event
         */
        $eventManager->attach('fafs.increment', function(Event $e) use ($services) {
            $params = $e->getParams();
            if(!isset($params['key'])) {
                throw new RuntimeException('No key');
            }
            $key = $params['key'];
            $count = isset($params['count']) ? $params['count'] : 1;
            $service = $services->get('FAFSClient\Service\FAFSClientService');
            $service->addCounter($key, $count);
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'FAFSClient\Service\FAFSClientService' => function(ServiceManager $sm) {
                    $config = $sm->get('config');
                    if(isset($config['fafs-client'])) {
                        $serviceConfig = $config['fafs-client'];
                        $service = new FAFSClientService($serviceConfig);
                        return $service;
                    }
                    throw new \InvalidArgumentException('No valid config found at key fafs-client');
                },
            ),
            'initializers' => array(
                'fafs' => function($service, $sm) {
                    if($service instanceof FafsAwareInterface) {
                        $fafsService = $sm->get('FAFSClient\Service\FAFSClientService');
                        $service->setFafs($fafsService);
                    }
                }
            )
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'getFafs' => function(PluginManager $pm) {
                    $fafsService = $pm->getServiceLocator()->get('FAFSClient\Service\FAFSClientService');
                    $plugin = new FafsPlugin($fafsService);
                    return $plugin;
                }
            )
        );
    }

}