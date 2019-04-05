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
 * User Agent Provider
 */
class UserAgentProvider implements ProviderInterface
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
            case 'chrome':
                return $faker->chrome;
                break;
            case 'firefox':
                return $faker->firefox;
                break;
            case 'safari':
                return $faker->safari;
                break;
            case 'opera':
                return $faker->opera;
                break;
            default:
                return $faker->userAgent;
                break;
        }
    }
}