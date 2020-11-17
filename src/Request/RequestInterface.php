<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

/** Class RequestInterface */
interface RequestInterface
{
    /**
     * TODO think about name
     * @return string
     */
    public function getBody(): string;
}
