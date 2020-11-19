<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

/** Class RequestInterface */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string;

    /**
     * @return string
     */
    public function getBody(): string;
}
