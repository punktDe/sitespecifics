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
use Neos\Flow\ObjectManagement\DependencyInjection\DependencyProxy;
use Neos\Neos\Domain\Service\ConfigurationContentDimensionPresetSource;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Utility\ObjectAccess;
use PunktDe\SiteSpecifics\Service\DimensionCombinationService;

class SiteSpecificContentDimensionsHelper implements ProtectedContextAwareInterface
{

    /**
     * @Flow\InjectConfiguration(package="PunktDe.SiteSpecifics")
     * @var array
     */
    protected $settings;

    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionsPresetSource;

    /**
     * @var ConfigurationContentDimensionPresetSource
     */
    protected $alteredContentDimensionsPresetSource;

    public function contentDimensionsByNameForSite(NodeInterface $site): array
    {
        return $this->getAlteredPresetSource($site)->getAllPresets();
    }

    /**
     * @param array $dimensions Dimension values indexed by dimension name
     * @param NodeInterface $site
     * @return array Allowed preset names for the given dimension combination indexed by dimension name
     */
    public function allowedPresetsByNameForSite(array $dimensions, NodeInterface $site): array
    {
        $allowedPresets = [];
        $preselectedDimensionPresets = [];
        foreach ($dimensions as $dimensionName => $dimensionValues) {
            $preset = $this->getAlteredPresetSource($site)->findPresetByDimensionValues($dimensionName, $dimensionValues);
            if ($preset !== null) {
                $preselectedDimensionPresets[$dimensionName] = $preset['identifier'];
            }
        }
        foreach ($preselectedDimensionPresets as $dimensionName => $presetName) {
            $presets = $this->getAlteredPresetSource($site)->getAllowedDimensionPresetsAccordingToPreselection($dimensionName, $preselectedDimensionPresets);
            $allowedPresets[$dimensionName] = array_keys($presets[$dimensionName]['presets']);
        }
        return $allowedPresets;
    }

    protected function getAlteredPresetSource(NodeInterface $site): ContentDimensionPresetSourceInterface
    {
        if ($this->alteredContentDimensionsPresetSource instanceof ContentDimensionPresetSourceInterface) {
            return $this->alteredContentDimensionsPresetSource;
        }

        if($this->contentDimensionsPresetSource instanceof DependencyProxy) {
            $this->contentDimensionsPresetSource->_activateDependency();
        }

        $this->alteredContentDimensionsPresetSource = clone $this->contentDimensionsPresetSource;
        $originalConfiguration = ObjectAccess::getProperty($this->contentDimensionsPresetSource, 'configuration', true);

        $dimensionSelectorOverride = $this->settings[$site->getContext()->getCurrentSite()->getName()]['dimensionSelector'] ?? null;
        $alteredConfiguration = DimensionCombinationService::adjustDimensionCombinations($originalConfiguration, $dimensionSelectorOverride);
        $this->alteredContentDimensionsPresetSource->setConfiguration($alteredConfiguration);

        return $this->alteredContentDimensionsPresetSource;
    }


    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
