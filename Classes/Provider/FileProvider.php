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
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WIND\Randomdata\Exception\ProviderException;
use WIND\Randomdata\Service\RandomdataService;

/**
 * File Provider
 */
class FileProvider implements ProviderInterface
{
    /**
     * Generate
     *
     * @param Generator $faker
     * @param array $configuration
     * @param RandomdataService $randomdataService
     * @return string
     * @throws ProviderException
     */
    static public function generate(Generator $faker, array $configuration, RandomdataService $randomdataService)
    {
        $configuration = array_merge([
            'minimum' => 1,
            'maximum' => 1,
            'referenceFields' => [],
        ], $configuration);

        // @todo Allow non fal files

        if (is_numeric($configuration['__recordUid'])) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
            $queryBuilder->select('uid')->from('sys_file_reference')->where(
                $queryBuilder->expr()->eq('uid_foreign', (int)$configuration['__recordUid']),
                $queryBuilder->expr()->eq('tablenames', (int)$configuration['__table']),
                $queryBuilder->expr()->eq('fieldname', (int)$configuration['__field'])
            );
            $rows = $queryBuilder->execute();
            foreach ($rows as $row) {
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['randomdata']['cmdMap']['sys_file_reference'][$row['uid']] = [
                    'delete' => 1,
                ];
            }
        }

        if (!empty($configuration['source'])) {
            $sourceAbsolutePath = PATH_site . trim($configuration['source'], '/') . '/';
            if (is_dir($sourceAbsolutePath)) {
                $count = $faker->numberBetween($configuration['minimum'], $configuration['maximum']);
                $files = self::getRandomFiles($sourceAbsolutePath, $count);

                if (!empty($files)) {
                    $resourceFactory = ResourceFactory::getInstance();
                    $references = [];
                    foreach ($files as $file) {
                        $fileObject = $resourceFactory->retrieveFileOrFolderObject($file);
                        $referenceUid = 'NEW99999' . count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['randomdata']['addToDataMap']['sys_file_reference']);
                        $referenceFieldValues = [
                            'table_local' => 'sys_file',
                            'uid_local' => $fileObject->getUid(),
                            'tablenames' => $configuration['__table'],
                            'uid_foreign' => $configuration['__recordUid'],
                            'fieldname' => $configuration['__field'],
                            'pid' => $configuration['__pid'],
                        ];
                        foreach ($configuration['referenceFields'] as $referenceField => $referenceFieldConfiguration) {
                            $referenceFieldValues[$referenceField] = $randomdataService->generateData('FileProvider:sys_file_reference', $referenceField, $referenceFieldConfiguration, $configuration['__pid']);
                        }
                        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['randomdata']['addToDataMap']['sys_file_reference'][$referenceUid] = $referenceFieldValues;
                        $references[] = $referenceUid;
                    }

                    return implode(',', $references);
                }
            }
        }

        return '';
    }

    /**
     * Get random files
     *
     * @param string $source
     * @param int $count
     * @return array
     */
    static protected function getRandomFiles($source, $count)
    {
        if ($count < 1) {
            return [];
        }

        $files = array_filter(glob($source . '*', GLOB_MARK), function($item) {
            return substr($item, -1) !== '/';
        });

        shuffle($files);

        if ($count > count($files)) {
            return $files;
        }

        return array_slice($files, -$count);
    }
}