<?php

namespace ZfSnapPhpDebugBar\Listener;

use DebugBar\JavascriptRenderer;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

/**
 * @author witold
 */
class RenderOnShutdownListener extends AbstractListenerAggregate
{
    /**
     * @var JavascriptRenderer
     */
    private $javascriptRenderer;
    /**
     * @var bool
     */
    private $enable;

    public function __construct(JavascriptRenderer $javascriptRenderer, $enable)
    {
        $this->javascriptRenderer = $javascriptRenderer;
        $this->enable = $enable;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        if ($this->enable === true) {
            $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, [$this, 'renderOnShutdown'], $priority);
        }
    }

    public function renderOnShutdown(MvcEvent $event)
    {
        $response = $event->getResponse();

        if (!$response instanceof Response || $event->getViewModel()->terminate()) {
            return;
        }
        $contentTypeHeader = $response->getHeaders()->get('Content-type');
        
        if (
            $contentTypeHeader
            // compatibility with middleware Content-type
            && stripos($contentTypeHeader->getFieldValue(), 'text/html') !== 0
        ) {
            return;
        }

        $this->javascriptRenderer->renderOnShutdown(false);
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }
}
