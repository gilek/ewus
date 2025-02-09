<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use DOMElement;
use Gilek\Ewus\Shared\Exception\InvalidDateException;
use Gilek\Ewus\Shared\Factory\DateTimeFactory;
use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\InsuranceStatus;
use Gilek\Ewus\Response\Operation;
use Gilek\Ewus\Response\Patient;
use Gilek\Ewus\Response\PatientInformation;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use Gilek\Ewus\Xml\XmlReader;

class CheckCwuResponseFactory
{
    private const NS_EWUS_PREFIX = 'ewus';

    public function __construct(
        private readonly XmlReaderFactory $xmlReaderFactory,
        private readonly ErrorParserService $errorParserService,
        private readonly DateTimeFactory $dateTimeFactory
    ) {
    }

    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function build(string $responseBody): CheckCwuResponse
    {
        try {
            $xmlReader = $this->xmlReaderFactory->create($responseBody, [self::NS_EWUS_PREFIX => Ns::EWUS]);
            $this->errorParserService->throwErrorIfExist($xmlReader);

            return new CheckCwuResponse(
                $this->extractOperation($xmlReader),
                $this->extractStatusCode($xmlReader),
                $this->extractPesel($xmlReader),
                $this->extractPatient($xmlReader)
            );
        } catch (
            EmptyResponseException |
            InvalidResponseContentException |
            ElementNotFoundException |
            InvalidDateException $exception
        ) {
            throw new InvalidResponseException('Invalid "checkCwu" response.', 0, $exception);
        }
    }

    /**
     * @throws ElementNotFoundException
     * @throws InvalidDateException
     */
    private function extractOperation(XmlReader $xmrReader): Operation
    {
        /** @var DOMElement $element */
        $element = $xmrReader->getElement($this->q('status_cwu_odp[1]'));

        return new Operation(
            $element->getAttribute('id_operacji'),
            $this->dateTimeFactory->createDateTime($element->getAttribute('data_czas_operacji'))
        );
    }

    /**
     * @throws ElementNotFoundException
     */
    private function extractStatusCode(XmlReader $xmlReader): int
    {
        return (int)$xmlReader->getElementValue($this->q('status_cwu[1]'));
    }

    /**
     * @throws ElementNotFoundException
     */
    private function extractPesel(XmlReader $xmlReader): string
    {
        return $xmlReader->getElementValue($this->q('numer_pesel[1]'));
    }

    /**
     * @throws ElementNotFoundException
     * @throws InvalidDateException
     */
    private function extractPatient(XmlReader $xmlReader): ?Patient
    {
        try {
            $xmlReader->getElement($this->q('pacjent[1]'));
        } catch (ElementNotFoundException $exception) {
            return null;
        }

        $expirationDate = $xmlReader->getElementValue($this->q('pacjent[1]/data_waznosci_potwierdzenia[1]'));

        return new Patient(
            $this->dateTimeFactory->createDate($expirationDate),
            $this->extractInsuranceStatus($xmlReader),
            $xmlReader->getElementValue($this->q('pacjent[1]/imie[1]')),
            $xmlReader->getElementValue($this->q('pacjent[1]/nazwisko[1]')),
            $this->extractInformation($xmlReader)
        );
    }

    /**
     * @throws ElementNotFoundException
     */
    private function extractInsuranceStatus(XmlReader $xmlReader): InsuranceStatus
    {
        /** @var DOMElement $insurance */
        $insurance = $xmlReader->getElement($this->q('pacjent[1]/status_ubezp[1]'));

        return new InsuranceStatus(
            (int)$insurance->nodeValue,
            $insurance->getAttribute('ozn_rec') === 'DN'
        );
    }

    private function q(string $queryPart): string
    {
        $parts = explode('/', $queryPart);

        $query = '/';
        foreach ($parts as $part) {
            $query .= '/' . self::NS_EWUS_PREFIX . ':' . $part;
        }

        return $query;
    }

    /**
     * @return PatientInformation[]
     */
    private function extractInformation(XmlReader $xmlReader): array
    {
        try {
            $infoElements = $xmlReader->getElements($this->q('pacjent[1]/informacje_dodatkowe/informacja'));
        } catch (ElementNotFoundException) {
            return [];
        }

        $infos = [];
        /** @var DOMElement $infoElement */
        foreach ($infoElements as $infoElement) {
            $infos[] = new PatientInformation(
                $infoElement->getAttribute('kod'),
                $infoElement->getAttribute('poziom'),
                $infoElement->getAttribute('wartosc')
            );
        }

        return $infos;
    }
}
