<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\Eel;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Utility\Arrays;

class SiteSpecificDimensionFilter implements ProtectedContextAwareInterface
{

    /**
     * @Flow\InjectConfiguration(package="PunktDe.SiteSpecifics")
     * @var array
     */
    protected $settings;

    public function filter(array $dimensions, NodeInterface $site): array
    {

        $dimensionSelectorOverride = Arrays::getValueByPath($this->settings, $site->getContext()->getCurrentSite()->getName() . '.dimensionSelector');

        if ($dimensionSelectorOverride === null) {
            return $dimensions;
        }

        $filteredDimensions = Arrays::arrayMergeRecursiveOverrule($dimensions, $dimensionSelectorOverride);

        foreach ($filteredDimensions as $dimensionKey => $dimensionConfiguration) {
            $filteredDimensions[$dimensionKey]['presets'] = array_filter($filteredDimensions[$dimensionKey]['presets']);
        }

        return $filteredDimensions;
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
