<?php

namespace ZfSnapPhpDebugBar\Listener;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Vasily Belosloodcev <vasily.belosloodcev@gmail.com>
 */
final class RenderOnShutdownListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, RenderOnShutdownListener::class);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \DebugBar\DebugBar $debugbar */
        $debugbar = $container->get('DebugBar');
        $config = $container->get('config')['php-debug-bar'];

        return new RenderOnShutdownListener($debugbar->getJavascriptRenderer(), $config['render-on-shutdown']);
    }
}
