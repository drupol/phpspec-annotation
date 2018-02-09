# PHPSpec annotation extension

A [PHPSpec](http://www.phpspec.net) extension that allows you to use annotated methods for tests.

## Installation

```yaml
composer require drupol/phpspec-annotation --dev
```

## Usage

Enable extension in `phpspec.yml` (or `phpspec.yml.dist`) file:

```yaml
extensions:
  drupol\PhpspecAnnotation\PhpspecAnnotation: ~
```

Then, you can use the annotation `@name` in the documentation block of your spec methods.

Example, instead of writing:

```php
public function it_can_read_an_xml_file_with_specific_settings() {
  // test code here
}
```

You can now write:

```php
/**
 * @name It can read an xml file with specific settings.
 */
public function readXmlFile() {
  // test code here
}
```

# Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)
