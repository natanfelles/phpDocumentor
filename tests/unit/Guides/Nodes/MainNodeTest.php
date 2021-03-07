<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Nodes;

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

use PHPUnit\Framework\TestCase;

final class MainNodeTest extends TestCase
{
    public function test_it_can_be_created_with_a_value() : void
    {
        $node = new MainNode('id');

        self::assertSame('id', $node->getValue());
        self::assertSame('id', $node->getValueString());
    }
}
