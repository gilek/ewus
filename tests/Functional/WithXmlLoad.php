<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional;

use ReflectionClass;
use ReflectionException;

trait WithXmlLoad
{
    /**
     * @param string $filename
     *
     * @return string
     *
     * @throws ReflectionException
     */
    private function loadXml(string $filename): string
    {
        $reflection = new ReflectionClass(get_class($this));
        $dir = dirname($reflection->getFileName());

        return file_get_contents($dir . '/__data__/' . $filename . '.xml');
    }
}
