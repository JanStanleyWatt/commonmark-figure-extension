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

namespace JSW\Figure\Renderer;

use JSW\Figure\Node\FigureCaption;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;

final class FigureCaptionRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    /**
     * @param FigureCaption $node
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        FigureCaption::assertInstanceOf($node);

        $attrs = $node->data->get('attributes');

        return new HtmlElement('figcaption', $attrs, $childRenderer->renderNodes($node->children()));
    }

    public function getXmlTagName(Node $node): string
    {
        return 'figcaption';
    }

    public function getXmlAttributes(Node $node): array
    {
        return [];
    }
}
