<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\Service;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Utility\Arrays;

class DimensionCombinationService
{

    public static function adjustDimensionCombinations(array $originalDimensionCombinations, ?array $siteSpecificOverride): array
    {
        if ($siteSpecificOverride === null) {
            return $originalDimensionCombinations;
        }

        $filteredDimensions = Arrays::arrayMergeRecursiveOverrule($originalDimensionCombinations, $siteSpecificOverride);

        foreach ($filteredDimensions as $dimensionKey => $dimensionConfiguration) {
            $filteredDimensions[$dimensionKey]['presets'] = array_filter($filteredDimensions[$dimensionKey]['presets']);
        }

        return $filteredDimensions;
    }
}
