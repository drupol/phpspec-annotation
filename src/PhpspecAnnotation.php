<?php

namespace drupol\PhpspecAnnotation;

use drupol\PhpspecAnnotation\Listeners\PhpspecAnnotationListener;
use PhpSpec\ServiceContainer;

class PhpspecAnnotation implements \PhpSpec\Extension
{
  /**
   * @param ServiceContainer $container
   */
  public function load(ServiceContainer $container, array $params)
  {
    $container->define('event_dispatcher.listeners.annotation', function () {
        return new PhpspecAnnotationListener();
      },
      ['event_dispatcher.listeners']
    );
  }
}
