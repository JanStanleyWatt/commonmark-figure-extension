# Commonmark Figure Extension

[League\CommonMark](https://github.com/thephpleague/commonmark)extension for HTML diagrams and captions inspired by [Markdig](https://github.com/xoofx/markdig).

## Installation
```Console
$ composer require jsw/commonmark-figure-extension
```

## Usage

- Fence the top and bottom of the sentence you want to be a figure with `^` like a code block
- Continued text in bottom fence becomes figure caption
- Even if the number of upper and lower `^` is different, it will be processed normally

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

// <figure><p><img src="https://example.com/image.jpg" alt="example-image" /></p>
// <figcaption>This is caption for image</figcaption></figure>
echo $converter->convert($markdown);
```

## License
Apache-v2