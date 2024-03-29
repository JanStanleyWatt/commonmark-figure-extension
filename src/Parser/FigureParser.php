<?php

declare(strict_types=1);
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

namespace JSW\Figure\Parser;

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

final class FigureParser extends AbstractBlockContinueParser implements BlockContinueParserWithInlinesInterface
{
    private Figure $block;
    private string $caption;

    public function __construct()
    {
        $this->block = new Figure();
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

                return BlockStart::of(new FigureParser())->at($cursor);
            }
        };
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ('^' === $cursor->getNextNonSpaceCharacter()) {
            $cursor->advanceToNextNonSpaceOrTab();

            if (null !== $cursor->match('/^\^{3,}/u') && !$cursor->isAtEnd()) {
                $this->caption = $cursor->getRemainder();
            }

            return BlockContinue::finished();
        }

        return BlockContinue::at($cursor);
    }

    public function parseInlines(InlineParserEngineInterface $inlineParser): void
    {
        if ('' !== $this->caption) {
            $this->block->appendChild(new FigureCaption());
            $inlineParser->parse($this->caption, $this->block->lastChild());
        }
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
}
