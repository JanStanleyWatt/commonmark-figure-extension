<?php

/**
 * Copyright 2023 Jan Stanley Watt

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at

 *  http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use JSW\Figure\FigureExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

$environment = new Environment();

$environment->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FigureExtension());

$converter = new MarkdownConverter($environment);

$markdown = <<<EOL
^^^
![example-image](https://example.com/image.jpg)
^^^ This is caption for image
EOL;

// <figure><p><img src="https://example.com/image.jpg" alt="example-image" /></p>
// <figcaption>This is caption for image</figcaption></figure>
echo $converter->convert($markdown);
