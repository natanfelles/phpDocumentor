<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Guides\Nodes;

use phpDocumentor\Guides\Environment;
use function array_unshift;
use function count;
use function get_class;
use function is_string;

class DocumentNode extends Node
{
    /** @var Node[] */
    protected $headerNodes = [];

    /** @var Node[] */
    protected $nodes = [];

    /**
     * @return Node[]
     */
    public function getHeaderNodes() : array
    {
        return $this->headerNodes;
    }

    /**
     * @return Node[]
     */
    public function getNodes(?callable $function = null) : array
    {
        $nodes = [];

        if ($function === null) {
            return $this->nodes;
        }

        foreach ($this->nodes as $node) {
            if (!$function($node)) {
                continue;
            }

            $nodes[] = $node;
        }

        return $nodes;
    }

    public function getTitle() : ?TitleNode
    {
        foreach ($this->nodes as $node) {
            if ($node instanceof TitleNode && $node->getLevel() === 1) {
                return $node;
            }
        }

        return null;
    }

    /**
     * @return TocNode[]
     */
    public function getTocs() : array
    {
        return $this->getNodes(
            static function ($node) {
                return $node instanceof TocNode;
            }
        );
    }

    /**
     * @return string[][]
     */
    public function getTitles() : array
    {
        $titles = [];
        $levels = [&$titles];

        foreach ($this->nodes as $node) {
            if (!($node instanceof TitleNode)) {
                continue;
            }

            $level = $node->getLevel();
            $text = $node->getValue()->getValue();
            $redirection = $node->getTarget();
            $value = $redirection !== '' ? [$text, $redirection] : $text;

            if (!isset($levels[$level - 1])) {
                continue;
            }

            $parent = &$levels[$level - 1];
            $element = [$value, []];
            $parent[] = $element;
            $levels[$level] = &$parent[count($parent) - 1][1];
        }

        return $titles;
    }

    /**
     * @param string|Node $node
     */
    public function addNode($node) : void
    {
        if (is_string($node)) {
            $node = new RawNode($node);
        }

        $this->nodes[] = $node;
    }

    public function prependNode(Node $node) : void
    {
        array_unshift($this->nodes, $node);
    }

    public function addHeaderNode(Node $node) : void
    {
        $this->headerNodes[] = $node;
    }
}
