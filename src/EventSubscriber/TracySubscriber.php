<?php

namespace Drupal\tracy\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\Debugger;

/**
 * @package Tracy
 * @author Michal Landsman <landsman@studioart.cz>
 */
class TracySubscriber implements EventSubscriberInterface {


    public function checkEnvironment()
    {
        //$config = \Drupal::config('tracy.settings');
        //print $config->get('message');

        //if (variable_get('traced_enabled', FALSE) && user_access('access traced debugger')) {
        //$mode = variable_get('traced_mode', 'DEVELOPMENT');

        //switch ($mode) {
        //case 'DEVELOPMENT':
        //Debugger::enable(Debugger::DEVELOPMENT);
        //break;
        //case 'PRODUCTION':
        //Debugger::enable(Debugger::PRODUCTION);
        //break;
        //default:
        //Debugger::enable(Debugger::DETECT);
        //break;
        //}

        //Debugger::$strictMode = variable_get('traced_strict_mode', FALSE);
        //}
    }


    public function initRegularTracy(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        Debugger::enable(Debugger::DEVELOPMENT);
        Debugger::getBar()->addPanel(new \Drupal\tracy\Helpers\TwigPanel(
            \Drupal::service('twig.profile')
        ));
    }

    public function initBlueScreenTracy(GetResponseEvent $event)
    {
        Debugger::enable(Debugger::DEVELOPMENT);
    }

    /**
     * Register subscriber
     * Hooks:
        KernelEvents::CONTROLLER
        The CONTROLLER event occurs once a controller was found for handling a request.
        KernelEvents::EXCEPTION
        The EXCEPTION event occurs when an uncaught exception appears.
        KernelEvents::FINISH_REQUEST
        The FINISH_REQUEST event occurs when a response was generated for a request.
        KernelEvents::REQUEST
        The REQUEST event occurs at the very beginning of request dispatching.
        KernelEvents::RESPONSE
        The RESPONSE event occurs once a response was created for replying to a request.
        KernelEvents::TERMINATE
        The TERMINATE event occurs once a response was sent.
        KernelEvents::VIEW
        The VIEW event occurs when the return value of a controller is not a Response instance.
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[KernelEvents::EXCEPTION][] = ['initBlueScreenTracy'];
        $events[KernelEvents::REQUEST][] = ['initRegularTracy'];


        return $events;
    }

}

