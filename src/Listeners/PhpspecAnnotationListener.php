<?php

namespace drupol\PhpspecAnnotation\Listeners;

use PhpSpec\Event\SuiteEvent;
use PhpSpec\Loader\Node\ExampleNode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PhpspecAnnotationListener implements EventSubscriberInterface
{

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents()
  {
    return [
      'beforeSuite' => ['beforeSuite', -100],
    ];
  }

  /**
   * @inheritDoc
   */
  public function beforeSuite(SuiteEvent $suiteEvent)
  {
    $suite = $suiteEvent->getSuite();

    foreach ($suite->getSpecifications() as $spec) {
      foreach ($spec->getClassReflection()->getMethods() as $method) {
        if (!preg_match('/^(it|its)[^a-zA-Z]/', $method->getName())) {
          if ($title = $this->getName($method->getDocComment())) {
            $spec->addExample(new ExampleNode($title, $method));
          }
        }
      }
    }
  }

  /**
   * Get the annotation.
   *
   * @param string $docComment
   *
   * @return string
   */
  protected function getName(string $docComment): string
  {
    return current(array_map(
      function($tag) {
        preg_match('#@name ([^ ].*)#', $tag, $match);

        return $match[1];
      },
      array_filter(
        array_map(
          'trim',
          explode(
            "\n",
            str_replace(
              "\r\n",
              "\n",
              $docComment
            )
          )
        ),
        function($docline) {
          return FALSE !== strpos($docline, '@name');
        }
      )
    ));
  }
}
