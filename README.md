# Commonmark Figure Extension

[League\CommonMark](https://github.com/thephpleague/commonmark) extension for HTML diagrams and captions inspired by [Markdig](https://github.com/xoofx/markdig).

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
            ->addExtension(new FigureExtension());

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

## Contribution
1. Fork it (https://github.com/JanStanleyWatt/commonmark-figure-extension)
1. Create your feature branch (`git checkout -b my-new-feature`)
1. Commit your changes (`git commit -am 'Add some feature'`)
1. Rebase your local changes against the master branch (if necessary)
1. Run test suite with the `composer test` command and confirm that it passe
1. Push to the branch (`git push origin my-new-feature`)
1. Create new Pull Request

## License
Apache-v2
