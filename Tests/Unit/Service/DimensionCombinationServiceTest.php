<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\Tests\Unit\Service;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Tests\UnitTestCase;
use Neos\Utility\Arrays;
use PunktDe\SiteSpecifics\Service\DimensionCombinationService;

class DimensionCombinationServiceTest extends UnitTestCase
{

    protected array $originalDimensionCombinations = [
        'country' => [
            'default' => 'countrySelect',
            'defaultPreset' => 'countrySelect',
            'label' => 'Country',
            'icon' => 'fas fa-atlas',
            'presets' => [
                'deu' => [
                    'label' => 'Germany',
                    'values' => ['deu'],
                    'uriSegment' => 'deu',
                    'constraints' => [
                        'language' => [
                            '*' => false,
                            'de' => true,
                            'en' => true,
                        ],
                    ],
                ],

                'fra' => [
                    'label' => 'France',
                    'values' => ['fra'],
                    'uriSegment' => 'fra',
                    'constraints' => [
                        'language' => [
                            '*' => false,
                            'fr' => true,
                        ],
                    ],
                ],
            ],
        ],
        'language' => [
            'default' => 'en',
            'defaultPreset' => 'en',
            'label' => 'Language',
            'icon' => 'fas fa-language',
            'presets' => [
                'de' => [
                    'label' => 'German',
                    'values' => ['de'],
                    'uriSegment' => 'de',
                ],
                'en' => [
                    'label' => 'English',
                    'values' => ['en'],
                    'uriSegment' => 'en',
                ],
                'fr' => [
                    'label' => 'French',
                    'values' => ['fr'],
                    'uriSegment' => 'fr',
                ],

            ],
        ],
    ];

    public function combinationOverrideDataProvider(): array
    {
        $originalCombinations = $this->originalDimensionCombinations;

        return [
            'preset can be removed' => [
                'override' => [
                    'country' => [
                        'presets' => [
                            'deu' => null,
                        ],
                    ],
                ],
                'expected' => Arrays::unsetValueByPath($this->originalDimensionCombinations, 'country.presets.deu'),
            ],
            'value can be set to false'=> [
                'override' => [
                    'country' => [
                        'presets' => [
                            'deu' => [
                                'constraints' => [
                                    'language' => [
                                        'en' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => Arrays::setValueByPath($this->originalDimensionCombinations, 'country.presets.deu.constraints.language.en', false),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider combinationOverrideDataProvider
     *
     * @param array $override
     * @param array $expected
     */
    public function adjustDimensionCombination(array $override, array $expected): void
    {
        self::assertEquals($expected, DimensionCombinationService::adjustDimensionCombinations($this->originalDimensionCombinations, $override));
    }

}
