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
    ): void
    {
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
        yield [
            $this->loadXml('check_cwu_insured_patient'),
            $this->createInsuredPatientResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_insured_patient_with_dn'),
            $this->createInsuredPatientWithDnResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_insured_patient_with_home_isolation'),
            $this->createInsuredPatientWithHomeIsolationResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_insured_patient_with_quarantine'),
            $this->createInsuredPatientWithQuarantineResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_insured_patient_from_ukraine'),
            $this->createInsuredPatientFromUkraineResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_insured_patient_from_ukraine_with_home_isolation'),
            $this->createInsuredPatientFromUkraineWithHomeIsolationResponse(),
        ];

        yield [
            $this->loadXml('check_cwu_patient_not_exist'),
            $this->createPatientNotExistResponse()
        ];

        yield [
            $this->loadXml('check_cwu_patient_with_annulled_pesel'),
            $this->createPatientWithAnnulledPeselResponse()
        ];

        yield [
            $this->loadXml('check_cwu_uninsured_patient'),
            $this->createUninsuredPatientResponse(),
        ];
    }

    private function createInsuredPatientResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            1,
            '79060804378',
            $this->createPatient(
                new InsuranceStatus(1, false)
            )
        );
    }

    private function createInsuredPatientWithDnResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            1,
            '19323197971',
            $this->createPatient(
                new InsuranceStatus(1, true)
            )
        );
    }

    private function createInsuredPatientWithHomeIsolationResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            1,
            '00102721595',
            $this->createPatient(
                new InsuranceStatus(1, false),
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
            $this->createOperation(),
            1,
            '00032948271',
            $this->createPatient(
                new InsuranceStatus(1, false),
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
            $this->createOperation(),
            1,
            '00032948271',
            $this->createPatient(
                new InsuranceStatus(1, false),
                [
                    new PatientInformation(
                        'URK',
                        'I',
                        'Pacjent posiada uprawnienie do świadczeń opieki zdrowotnej na mocy Ustawy z dnia 12 marca 2022 r. o pomocy obywatelom Ukrainy w związku z konfliktem zbrojnym na terytorium tego państwa',
                    )
                ]
            )
        );
    }

    private function createInsuredPatientFromUkraineWithHomeIsolationResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            1,
            '00032948271',
            $this->createPatient(
                new InsuranceStatus(1, false),
                [
                    new PatientInformation(
                        'URK',
                        'I',
                        'Pacjent posiada uprawnienie do świadczeń opieki zdrowotnej na mocy Ustawy z dnia 12 marca 2022 r. o pomocy obywatelom Ukrainy w związku z konfliktem zbrojnym na terytorium tego państwa',
                    ),
                    new PatientInformation(
                        'KWARANTANNA-COVID19',
                        'O',
                        'Pacjent objęty kwarantanną do dnia 06-12-2020'
                    )
                ]
            )
        );
    }

    private function createPatientNotExistResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            0,
            '01010153201',
            null
        );
    }

    private function createPatientWithAnnulledPeselResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            -1,
            '00060958187',
            null
        );
    }

    private function createUninsuredPatientResponse(): CheckCwuResponse
    {
        return new CheckCwuResponse(
            $this->createOperation(),
            1,
            '55021562501',
            $this->createPatient(
                new InsuranceStatus(0, false)
            )
        );
    }

    private function createOperation(): Operation
    {
        return new Operation(
            'L1712M01200000001',
            $this->createDateTime('2020-11-22 10:31:06.756')
        );
    }

    private function createDateTime(string $dateTime): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTime, new DateTimeZone('Europe/Warsaw'));
    }

    /**
     * @param array<PatientInformation> $info
     */
    private function createPatient(InsuranceStatus $insuranceStatus, array $info = []): Patient
    {
        $insured = $insuranceStatus->getCode() === 1;

        return new Patient(
            $this->createDateTime('2020-11-22'),
            $insuranceStatus,
            $insured ? 'ImięTAK' : 'ImięNIE',
            $insured ? 'NazwiskoTAK' : 'NazwiskoNIE',
            $info
        );
    }
}
