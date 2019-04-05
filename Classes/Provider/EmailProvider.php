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

/**
 * Email Provider
 */
class EmailProvider implements ProviderInterface
{
    /**
     * Generate
     *
     * @param \Faker\Generator $faker
     * @param array $configuration
     * @return string
     */
    static public function generate(Generator $faker, array $configuration = [])
    {
        $configuration = array_merge([
            'type' => null,
        ], $configuration);

        switch ($configuration['type']) {
            case 'safe':
                return $faker->safeEmail;
                break;
            case 'free':
                return $faker->freeEmail;
                break;
            case 'company':
                return $faker->companyEmail;
                break;
            default:
                return $faker->email;
                break;
        }
    }
}