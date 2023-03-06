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

namespace JSW\Figure;

use JSW\Figure\Node\Figure;
use JSW\Figure\Node\FigureCaption;
use JSW\Figure\Parser\Block\FigureBlockParser;
use JSW\Figure\Renderer\FigureCaptionRenderer;
use JSW\Figure\Renderer\FigureRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

final class FigureExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(FigureBlockParser::createBlockStartParser())
                    ->addRenderer(Figure::class, new FigureRenderer())
                    ->addRenderer(FigureCaption::class, new FigureCaptionRenderer());
    }
}
