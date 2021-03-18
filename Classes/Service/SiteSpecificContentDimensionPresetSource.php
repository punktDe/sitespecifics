<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\Service;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ConfigurationContentDimensionPresetSource;

/**
 * @Flow\Scope("singleton")
 */
class SiteSpecificContentDimensionPresetSource extends ConfigurationContentDimensionPresetSource
{

    /**
     * @Flow\Inject
     * @var SiteDeterminationService
     */
    protected $siteDeterminationService;

    /**
     * @Flow\InjectConfiguration(package="PunktDe.SiteSpecifics")
     * @var array
     */
    protected $settings;

    protected bool $configurationAdjusted = false;

    public function initializeObject(): void
    {
        $siteName = $this->siteDeterminationService->getCurrentSiteName();

        if ($siteName !== null) {
            $this->alterPresetSourceForSite($siteName);
        }
    }

    public function alterPresetSourceForSite(string $siteName): void
    {
        if ($this->configurationAdjusted) {
            return;
        }

        $dimensionSelectorOverride = $this->settings[$siteName]['dimensionSelector'] ?? null;

        if ($dimensionSelectorOverride === null) {
            return;
        }

        $this->configuration = DimensionCombinationService::adjustDimensionCombinations($this->configuration, $dimensionSelectorOverride);
        $this->configurationAdjusted = true;
    }
}
