<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

use DateTimeImmutable;
use DOMNode;
use Gilek\Ewus\Ns;

class CheckCwuResponseFactory
{
    private const NS_EWUS_PREFIX = 'ewus';

    /** @var XmlReaderFactory */
    private $xmlReaderFactory;

    /**
     * @param XmlReaderFactory $xmlReaderFactory
     */
    public function __construct(XmlReaderFactory $xmlReaderFactory)
    {
        $this->xmlReaderFactory = $xmlReaderFactory;
    }

    /**
     * @param string $responseBody
     *
     * @return CheckCwuResponse
     *
     * @throws InvalidResponseException
     */
    public function build(string $responseBody): CheckCwuResponse
    {
        try {
            $xmlReader = $this->xmlReaderFactory->create($responseBody, [self::NS_EWUS_PREFIX => Ns::EWUS]);

            return new CheckCwuResponse(
                $this->extractOperation($xmlReader),
                $this->extractStatusCode($xmlReader),
                $this->extractPesel($xmlReader),
                $this->extractPatient($xmlReader)
            );
        } catch (EmptyResponseException | InvalidResponseFormatException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "checkCwu" response.', 0, $exception);
        }
    }

    /**
     * @param XmlReader $xmrReader
     *
     * @return Operation
     *
     * @throws ElementNotFoundException
     */
    private function extractOperation(XmlReader $xmrReader): Operation
    {
        $element = $xmrReader->getElement($this->q('status_cwu_odp[1]'));

        return new Operation(
            $element->getAttribute('id_operacji'),
            new DateTimeImmutable() //TODO fixme DateTimeImmutable::createFromFormat('c', $element->getAttribute('data_czas_operacji'))
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
        return (int) $xmlReader->getElementValue($this->q('status_cwu[1]'));
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
            new DateTimeImmutable(), //TODO fixme DateTimeImmutable::createFromFormat('c', $expirationDate),
            (int) $xmlReader->getElementValue($this->q('pacjent[1]/status_ubezp[1]')),
            $xmlReader->getElementValue($this->q('pacjent[1]/imie[1]')),
            $xmlReader->getElementValue($this->q('pacjent[1]/nazwisko[1]')),
            $this->extractInformation($xmlReader)
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
        /** @var DOMNode $infoElement */
        foreach ($infoElements as $infoElement) {
            $infos[] = new PatientInformation(
                $infoElement->getAttribute('kod'),
                (int) $infoElement->getAttribute('poziom'),
                $infoElement->getAttribute('wartosc')
            );
        }

        return $infos;
    }
}
