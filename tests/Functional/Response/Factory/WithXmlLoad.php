<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

trait WithXmlLoad
{
    /**
     * @param $filename
     *
     * @return string
     */
    private function loadXml($filename): string
    {
        return file_get_contents(__DIR__ . '/__data__/' . $filename . '.xml');
    }
}