<?php

namespace drupol\PhpspecAnnotation\Listeners;

use PhpSpec\Event\SpecificationEvent;
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
      'beforeSpecification' => ['beforeSpecification', -100],
    ];
  }

  /**
   * @inheritDoc
   */
  public function beforeSpecification(SpecificationEvent $specificationEvent) {
    $spec = $specificationEvent->getSpecification();

    foreach ($spec->getClassReflection()->getMethods() as $method) {
      if (!preg_match('/^(it|its)[^a-zA-Z]/', $method->getName())) {
        if ($title = $this->getName($method->getDocComment())) {
          $spec->addExample(new ExampleNode($title, $method));
        }
      }
    }

    foreach ($spec->getExamples() as $example) {
      if ($title = $this->getName($example->getFunctionReflection()->getDocComment())) {
        $example->setTitle($title);
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
