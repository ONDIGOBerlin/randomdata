<?php
namespace WIND\Randomdata\Provider;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Faker\Generator;
use WIND\Randomdata\Service\RandomdataService;

/**
 * Words Provider
 */
class WordsProvider implements ProviderInterface
{
    /**
     * Generate
     *
     * @param Generator $faker
     * @param array $configuration
     * @param RandomdataService $randomdataService
     * @param array $previousFieldsData
     * @return string
     */
    static public function generate(Generator $faker, array $configuration, RandomdataService $randomdataService, array $previousFieldsData)
    {
        $configuration = array_merge([
            'minimum' => 1,
            'maximum' => 5,
        ], $configuration);

        $count = rand($configuration['minimum'], $configuration['maximum']);

        return $faker->words($count, true);
    }
}