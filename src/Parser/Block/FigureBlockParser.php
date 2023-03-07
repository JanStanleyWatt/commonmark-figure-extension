<?php

declare(strict_types=1);
/**
 * Copyright 2021 whojinn.
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

namespace JSW\Figure\Parser\Block;

use JSW\Figure\Node\Figure;
use JSW\Figure\Node\FigureCaption;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockContinueParserWithInlinesInterface;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\InlineParserEngineInterface;
use League\CommonMark\Parser\MarkdownParserStateInterface;

final class FigureBlockParser extends AbstractBlockContinueParser implements BlockContinueParserWithInlinesInterface
{
    private Figure $block;

    /**
     * @var string[] strings
     */
    private array $strings;

    private string $caption;

    public function __construct()
    {
        $this->block = new Figure();
        $this->strings = [];
        $this->caption = '';
    }

    public static function createBlockStartParser(): BlockStartParserInterface
    {
        return new class() implements BlockStartParserInterface {
            public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
            {
                if ('^' !== $cursor->getNextNonSpaceCharacter()) {
                    return BlockStart::none();
                }

                if (null === $cursor->match('/^[\s\t]*\^{3,}$/u')) {
                    return BlockStart::none();
                }

                return BlockStart::of(new FigureBlockParser())->at($cursor);
            }
        };
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        $cursor->advanceToNextNonSpaceOrTab();

        if ('^' === $cursor->getCurrentCharacter()) {
            if (null !== $cursor->match('/^\^{3,}/u') && !$cursor->isAtEnd()) {
                $this->caption = $cursor->getRemainder();
            }

            return BlockContinue::finished();
        }

        return BlockContinue::at($cursor);
    }

    public function parseInlines(InlineParserEngineInterface $inlineParser): void
    {
        foreach ($this->strings as $string) {
            $inlineParser->parse($string, $this->block);
        }
        if ('' !== $this->caption) {
            $this->block->appendChild(new FigureCaption());
            $inlineParser->parse($this->caption, $this->block->lastChild());
        }
    }

    public function closeBlock(): void
    {
    }

    public function addLine(string $line): void
    {
        $this->strings[] = $line;
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        if ($childBlock instanceof Figure) {
            return false;
        }

        return true;
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return true;
    }
}
