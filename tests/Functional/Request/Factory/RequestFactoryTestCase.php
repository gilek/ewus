<?php
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
