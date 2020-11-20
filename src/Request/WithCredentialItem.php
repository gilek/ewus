<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Request\Credentials;

trait WithCredentialItem
{
    /**
     * @param Credentials $credentials
     * @param string      $ns
     *
     * @return array[] // TODO return type
     */
    private function generateCredentialItems(Credentials $credentials, string $ns): array
    {
        $items = [
            $this->createCredentialItem($ns, 'login', $credentials->getLogin()),
            $this->createCredentialItem(
                $ns,
                'domain',
                sprintf('%02d', $credentials->getDomain())
            ),
        ];

        if (null !== $type = $credentials->getType()) {
            $items[] = $this->createCredentialItem($ns,'type', $type);
        }

        if (null !== $idntLek = $credentials->getIdntLek()) {
            $items[] = $this->createCredentialItem($ns, 'idntLek', (string) $idntLek);
        }

        if (null !== $idntSwd = $credentials->getIdntSwd()) {
            $items[] = $this->createCredentialItem($ns,'idntSwd', (string) $idntSwd);
        }

        return $items;
    }

    /**
     * @param string $ns
     * @param string $name
     * @param string $value
     * @param bool   $stringValue
     *
     * @return array<string, array<string, string>>
     */
    private function createCredentialItem(string $ns, string $name, string $value, bool $stringValue = false): array
    {
        $authNs = '{' . $ns . '}';

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
