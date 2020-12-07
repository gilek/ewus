<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use PHPStan\Testing\TestCase;

abstract class RequestFactoryTestCase extends TestCase
{
    /**
     * @param string $namespace
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    protected function node(string $namespace, string $name, array $attributes = [], $value = null): array
    {
        return [
            'name' => '{' . $namespace . '}' . $name,
            'value' => $value,
            'attributes' => $attributes
        ];
    }
}
