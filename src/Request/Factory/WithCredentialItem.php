<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;

trait WithCredentialItem
{
    /**
     * @return array<array<string, mixed>>
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
            $this->createCredentialItem($ns, 'type', $credentials->getType()->value)
        ];

        if (null !== $doctorId = $credentials->getDoctorId()) {
            $items[] = $this->createCredentialItem($ns, 'idntLek', $doctorId);
        }

        if (null !== $providerId = $credentials->getProviderId()) {
            $items[] = $this->createCredentialItem($ns, 'idntSwd', $providerId);
        }

        return $items;
    }

    /**
     * @return array<array<string, mixed>>
     */
    private function createCredentialItem(string $ns, string $name, string $value): array
    {
        $authNs = '{' . $ns . '}';

        return [
            $authNs . 'item' => [
                $authNs . 'name' => $name,
                $authNs . 'value' => [$authNs . 'stringValue' => $value],
            ],
        ];
    }
}
