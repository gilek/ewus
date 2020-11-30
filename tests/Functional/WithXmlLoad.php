<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional;

use ReflectionClass;

trait WithXmlLoad
{
    /**
     * @param $filename
     *
     * @return string
     */
    private function loadXml($filename): string
    {
        $reflection = new ReflectionClass(get_class($this));
        $dir = dirname($reflection->getFileName());

        return file_get_contents($dir . '/__data__/' . $filename . '.xml');
    }
}