<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

use DateTimeImmutable;
use DateTimeZone;
use Generator;
use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Factory\CheckCwuResponseFactory;
use Gilek\Ewus\Response\InsuranceStatus;
use Gilek\Ewus\Response\Operation;
use Gilek\Ewus\Response\Patient;
use Gilek\Ewus\Response\PatientInformation;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CheckCwuResponseFactoryTest extends TestCase
{
    use WithXmlLoad;

    private CheckCwuResponseFactory $sut;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new CheckCwuResponseFactory(
            new XmlReaderFactory(),
            new ErrorParserService(),
            new DateTimeFactory()
        );
    }

    #[Test]
    #[DataProvider('responseDataProvider')]
    public function is_should_create_correct_response(
        string $xml,
        CheckCwuResponse $expectedResponse
    ): void {
        $this->assertEquals(
            $expectedResponse,
            $this->sut->build($xml)
        );
    }

    /**
     * @return Generator<array>
     */
    public function responseDataProvider(): Generator
    {
        yield 'insured patient' => [
            $this->loadXml('check_cwu_insured_patient'),
            $this->createInsuredPatientResponse(),
        ];

        yield 'insured patient with dn' => [
            $this->loadXml('check_cwu_insured_patient_with_dn'),
            $this->createInsuredPatientWithDnResponse(),
        ];

        yield 'insured patient_with home isolation' => [
            $this->loadXml('check_cwu_insured_patient_with_home_isolation'),
            $this->createInsuredPatientWithHomeIsolationResponse(),
        ];

        yield 'insured patient with quarantine' => [
            $this->loadXml('check_cwu_insured_patient_with_quarantine'),
            $this->createInsuredPatientWithQuarantineResponse(),
        ];

        yield 'insured patient from ukraine' => [
            $this->loadXml('check_cwu_insured_patient_from_ukraine'),
            $this->createInsuredPatientFromUkraineResponse(),
        ];

        yield 'insured patient from ukraine with home isolation' => [
            $this->loadXml('check_cwu_insured_patient_from_ukraine_with_home_isolation'),
            $this->createInsuredPatientFromUkraineWithHomeIsolationResponse(),
        ];

        yield 'patient not exist' => [
            $this->loadXml('check_cwu_patient_not_exist'),
            $this->createPatientNotExistResponse(),
        ];

        yield 'patient with annulled pesel' => [
            $this->loadXml('check_cwu_patient_with_annulled_pesel'),
            $this->createPatientWithAnnulledPeselResponse(),
        ];

        yield 'uninsured patient' => [
            $this->loadXml('check_cwu_uninsured_patient'),
            $this->createUninsuredPatientResponse(),
        ];
    }

    private function createInsuredPatientResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            1,
            '79060804378',
            new Patient(
                $this->createDateTime('2020-11-22'),
                new InsuranceStatus(1, false),
                'ImięTAK',
                'NazwiskoTAK',
                []
            )
        );
    }

    private function createInsuredPatientWithDnResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            1,
            '19323197971',
            new Patient(
                $this->createDateTime('2020-11-22'),
                new InsuranceStatus(1, true),
                'ImięTAK',
                'NazwiskoTAK',
                []
            )
        );
    }

    private function createInsuredPatientWithHomeIsolationResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            1,
            '00102721595',
            new Patient(
                $this->createDateTime('2020-11-22'),
                new InsuranceStatus(1, false),
                'ImięTAK',
                'NazwiskoTAK',
                [
                    new PatientInformation(
                        'IZOLACJA DOMOWA',
                        'O',
                        'Pacjent podlega izolacji domowej do dnia 06-12-2020'
                    )
                ]
            )
        );
    }

    private function createInsuredPatientWithQuarantineResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            1,
            '00032948271',
            new Patient(
                $this->createDateTime('2020-11-22'),
                new InsuranceStatus(1, false),
                'ImięTAK',
                'NazwiskoTAK',
                [
                    new PatientInformation(
                        'KWARANTANNA-COVID19',
                        'O',
                        'Pacjent objęty kwarantanną do dnia 06-12-2020'
                    )
                ]
            )
        );
    }

    private function createInsuredPatientFromUkraineResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2025-02-09 13:50:10.271'),
            1,
            '03070665355',
            new Patient(
                $this->createDateTime('2025-02-09'),
                new InsuranceStatus(1, false),
                'ImięUKR',
                'NazwiskoUKR',
                [
                    new PatientInformation(
                        'UKR',
                        'I',
                        'Pacjent posiada uprawnienie do świadczeń opieki zdrowotnej na mocy '
                        . 'Ustawy z dnia 12 marca 2022 r. o pomocy obywatelom Ukrainy w związku z konfliktem '
                        . 'zbrojnym na terytorium tego państwa',
                    ),
                ]
            )
        );
    }

    private function createInsuredPatientFromUkraineWithHomeIsolationResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2025-02-09 13:52:44.979'),
            1,
            '02082642235',
            new Patient(
                $this->createDateTime('2025-02-09'),
                new InsuranceStatus(1, false),
                'ImięUKR',
                'NazwiskoUKR',
                [
                    new PatientInformation(
                        'UKR',
                        'I',
                        'Pacjent posiada uprawnienie do świadczeń opieki zdrowotnej na mocy '
                        . 'Ustawy z dnia 12 marca 2022 r. o pomocy obywatelom Ukrainy w związku z konfliktem '
                        . 'zbrojnym na terytorium tego państwa',
                    ),
                    new PatientInformation(
                        'IZOLACJA DOMOWA',
                        'O',
                        'Pacjent podlega izolacji domowej do dnia 23-02-2025'
                    )
                ]
            )
        );
    }

    private function createPatientNotExistResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            0,
            '01010153201',
            null
        );
    }

    private function createPatientWithAnnulledPeselResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            -1,
            '00060958187',
            null
        );
    }

    private function createUninsuredPatientResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation('2020-11-22 10:31:06.756'),
            1,
            '55021562501',
            new Patient(
                $this->createDateTime('2020-11-22'),
                new InsuranceStatus(0, false),
                'ImięNIE',
                'NazwiskoNIE',
                []
            )
        );
    }

    private function createOperation(string $date): Operation
    {
        return new Operation(
            'L1712M01200000001',
            $this->createDateTime($date)
        );
    }

    private function createDateTime(string $dateTime): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTime, new DateTimeZone('Europe/Warsaw'));
    }
}
