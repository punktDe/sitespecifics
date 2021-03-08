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
use PunktDe\SiteSpecifics\Service\DimensionCombinationService;

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
        return DimensionCombinationService::adjustDimensionCombinations($dimensions, $dimensionSelectorOverride);
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
