# Commonmark Figure Extension

[League\CommonMark](https://github.com/thephpleague/commonmark)extension for HTML diagrams and captions inspired by [Markdig](https://github.com/xoofx/markdig).

## Installation
```Console
$ composer require jsw/commonmark-figure-extension
```

## Usage
```php
$environment = new Environment();
$environment->addExtension(new CommonMarkCoreExtension());
            ->addExtension(new FitureExtension());

$converter = new MarkdownConverter($environment);

$markdown =<<<EOL
^^^
![example-image](https://example.com/image.jpg)
^^^ This is caption for image
EOL;

// <figure><img src="https://example.com/image.jpg" alt="example-image">
// <figcaption>This is caption for image</figcaption></figure>
echo $converter->convert($markdown);
```

## License
Apache-v2