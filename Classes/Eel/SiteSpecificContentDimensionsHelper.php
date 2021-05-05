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
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

class SiteSpecificContentDimensionsHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionsPresetSource;

    public function contentDimensionsByNameForSite(NodeInterface $site): array
    {
        $this->alteredPresetSource($site);
        return $this->contentDimensionsPresetSource->getAllPresets();
    }

    /**
     * @param array $dimensions Dimension values indexed by dimension name
     * @param NodeInterface $site
     * @return array Allowed preset names for the given dimension combination indexed by dimension name
     */
    public function allowedPresetsByNameForSite(array $dimensions, NodeInterface $site): array
    {
        $this->alteredPresetSource($site);

        $allowedPresets = [];
        $preselectedDimensionPresets = [];
        foreach ($dimensions as $dimensionName => $dimensionValues) {
            $preset = $this->contentDimensionsPresetSource->findPresetByDimensionValues($dimensionName, $dimensionValues);
            if ($preset !== null) {
                $preselectedDimensionPresets[$dimensionName] = $preset['identifier'];
            }
        }
        foreach ($preselectedDimensionPresets as $dimensionName => $presetName) {
            $presets = $this->contentDimensionsPresetSource->getAllowedDimensionPresetsAccordingToPreselection($dimensionName, $preselectedDimensionPresets);
            $allowedPresets[$dimensionName] = array_keys($presets[$dimensionName]['presets']);
        }
        return $allowedPresets;
    }

    protected function alteredPresetSource(NodeInterface $site): void
    {
        $this->contentDimensionsPresetSource->alterPresetSourceForSite($site->getContext()->getCurrentSite()->getName());
    }


    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
