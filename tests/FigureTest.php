<?php

declare(strict_types=1);

use JSW\Figure\FigureExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;

/**
 * Copyright 2023 Jan Stanley Watt.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
final class FigureTest extends TestCase
{
    public function testSimpleFigure()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect = "<figure><p>Figure</p></figure>\n";
        $actual_md = <<<EOL
        ^^^
        Figure
        ^^^
        EOL;
        $actual = $converter->convert($actual_md)->getContent();

        $this->assertSame($expect, $actual);
    }

    public function testSimpleFigureAndFigureCaption()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect = $expect = "<figure><p>Figure</p>\n<figcaption>Caption</figcaption></figure>\n";
        $actual_md = <<<EOL
        ^^^
        Figure
        ^^^Caption
        EOL;
        $actual = $converter->convert($actual_md)->getContent();

        $this->assertSame($expect, $actual);
    }

    public function testManySymbols()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect = "<figure><p>Figure</p></figure>\n";
        $actual_md = <<<EOL
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        Figure
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        EOL;
        $actual = $converter->convert($actual_md)->getContent();

        $this->assertSame($expect, $actual);
    }

    public function testManySymbolsWithFigureCaption()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect = $expect = "<figure><p>Figure</p>\n<figcaption>Caption</figcaption></figure>\n";
        $actual_md = <<<EOL
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        Figure
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^Caption
        EOL;
        $actual = $converter->convert($actual_md)->getContent();

        $this->assertSame($expect, $actual);
    }

    public function testDifferentNumberOfSymbols()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect_1 = "<figure><p>Figure</p></figure>\n";
        $actual_md_1 = <<<EOL
        ^^^^^
        Figure
        ^^^
        EOL;
        $actual_1 = $converter->convert($actual_md_1)->getContent();

        $expect_2 = "<figure><p>Figure</p></figure>\n";
        $actual_md_2 = <<<EOL
        ^^^
        Figure
        ^^^^^
        EOL;
        $actual_2 = $converter->convert($actual_md_2)->getContent();

        $this->assertSame($expect_1, $actual_1, 'Case with more upper is failed');
        $this->assertSame($expect_2, $actual_2, 'Case with more lower is failed');
    }

    public function testDifferentNumberOfSymbolsWithFigureCaption()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect_1 = $expect = "<figure><p>Figure</p>\n<figcaption>Caption</figcaption></figure>\n";
        $actual_md_1 = <<<EOL
        ^^^^^
        Figure
        ^^^Caption
        EOL;
        $actual_1 = $converter->convert($actual_md_1)->getContent();

        $expect_2 = $expect = "<figure><p>Figure</p>\n<figcaption>Caption</figcaption></figure>\n";
        $actual_md_2 = <<<EOL
        ^^^^^
        Figure
        ^^^Caption
        EOL;
        $actual_2 = $converter->convert($actual_md_2)->getContent();

        $this->assertSame($expect_1, $actual_1, 'Case with more upper is failed');
        $this->assertSame($expect_2, $actual_2, 'Case with more lower is failed');
    }

    public function testFigureWithCodeBlockRetainsIndent()
    {
        $environment = new Environment();

        $environment->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FigureExtension());

        $converter = new MarkdownConverter($environment);

        $expect = <<<EOL
        <figure><pre><code>if (condition) {
            doSomething();
        }
            // comment behind whitespace
        //     comment with whitespace
        // comment with trailing whitespace    
        </code></pre></figure>

        EOL;

        $actual_md = <<<EOL
        ^^^
        ```
        if (condition) {
            doSomething();
        }
            // comment behind whitespace
        //     comment with whitespace
        // comment with trailing whitespace    
        ```
        ^^^
        EOL;
        $actual = $converter->convert($actual_md)->getContent();

        $this->assertSame($expect, $actual);
    }
}
