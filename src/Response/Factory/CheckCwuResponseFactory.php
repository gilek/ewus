<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use DOMElement;
use Gilek\Ewus\Misc\Exception\InvalidDateException;
use Gilek\Ewus\Misc\Factory\DateTimeFactory;
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

    /** @var XmlReaderFactory */
    private $xmlReaderFactory;

    /** @var ErrorParserService */
    private $errorParserService;

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    /**
     * @param XmlReaderFactory $xmlReaderFactory
     * @param ErrorParserService $errorParserService
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        XmlReaderFactory $xmlReaderFactory,
        ErrorParserService $errorParserService,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->xmlReaderFactory = $xmlReaderFactory;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->errorParserService = $errorParserService;
    }

    /**
     * @param string $responseBody
     *
     * @return CheckCwuResponse
     *
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
        } catch (EmptyResponseException |
            InvalidResponseContentException |
            ElementNotFoundException |
            InvalidDateException $exception
        ) {
            throw new InvalidResponseException('Invalid "checkCwu" response.', 0, $exception);
        }
    }

    /**
     * @param XmlReader $xmrReader
     *
     * @return Operation
     *
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
     * @param XmlReader $xmlReader
     *
     * @return int
     *
     * @throws ElementNotFoundException
     */
    private function extractStatusCode(XmlReader $xmlReader): int
    {
        return (int)$xmlReader->getElementValue($this->q('status_cwu[1]'));
    }

    /**
     * @param XmlReader $xmlReader
     *
     * @return string
     *
     * @throws ElementNotFoundException
     */
    private function extractPesel(XmlReader $xmlReader): string
    {
        return $xmlReader->getElementValue($this->q('numer_pesel[1]'));
    }

    /**
     * @param XmlReader $xmlReader
     *
     * @return Patient|null
     *
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
     * @param XmlReader $xmlReader
     *
     * @return InsuranceStatus
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

    /**
     * @param string $queryPart
     *
     * @return string
     */
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
     * @param XmlReader $xmlReader
     *
     * @return PatientInformation[]
     */
    private function extractInformation(XmlReader $xmlReader): array
    {
        try {
            $infoElements = $xmlReader->getElements($this->q('pacjent[1]/informacje_dodatkowe/informacja'));
        } catch (ElementNotFoundException $exception) {
            return [];
        }

        $infos = [];
        /** @var DOMElement $infoElement */
        foreach ($infoElements as $infoElement) {
            $infos[] = new PatientInformation(
                $infoElement->getAttribute('kod'),
                (int)$infoElement->getAttribute('poziom'),
                $infoElement->getAttribute('wartosc')
            );
        }

        return $infos;
    }
}
