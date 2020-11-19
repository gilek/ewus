<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;

abstract class CredentialRequestBase
{
    protected const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';

    /**
     * @param Credentials $credentials
     *
     * @return array[]
     */
    protected function generateCredentialItems(Credentials $credentials): array
    {
        $items = [
            $this->createCredentialItem('login', $credentials->getLogin()),
            $this->createCredentialItem(
                'domain',
                sprintf('%02d', $credentials->getDomain())
            ),
        ];

        if (null !== $type = $credentials->getType()) {
            $items[] = $this->createCredentialItem('type', $type);
        }

        if (null !== $idntLek = $credentials->getIdntLek()) {
            $items[] = $this->createCredentialItem('idntLek', (string) $idntLek);
        }

        if (null !== $idntSwd = $credentials->getIdntSwd()) {
            $items[] = $this->createCredentialItem('idntSwd', (string) $idntSwd);
        }

        return $items;
    }

    /**
     * @param string $name
     * @param string $value
     * @param bool   $stringValue
     *
     * @return array<string, array<string, string>>
     */
    private function createCredentialItem(string $name, string $value, bool $stringValue = false): array
    {
        $authNs = '{' . self::NS_AUTH . '}';

        $elementValue = $stringValue
            ? [$authNs . 'stringValue' => $value]
            : $value;

        return [
            $authNs . 'item' => [
                $authNs . 'name' => $name,
                $authNs . 'value' => $elementValue,
            ],
        ];
    }
}
