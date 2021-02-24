<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\NodeTypePostProcessor;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\ServerRequest;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Utility\Arrays;
use PunktDe\SiteSpecifics\Service\SiteDeterminationService;

class SiteSpecificNodeTypePostProcessor implements NodeTypePostprocessorInterface
{
    /**
     * @Flow\InjectConfiguration(package="PunktDe.SiteSpecifics")
     * @var array
     */
    protected $siteSpecificConfiguration;

    /**
     * @Flow\Inject
     * @var SiteDeterminationService
     */
    protected $siteDeterminationService;

    public function process(NodeType $nodeType, array &$configuration, array $options)
    {

        $siteSpecificNodeTypeConfiguration = $this->siteSpecificConfiguration[$this->siteDeterminationService->getCurrentSiteName()]['nodeTypes'][$nodeType->getName()] ?? null;
        if ($siteSpecificNodeTypeConfiguration === null) {
            return;
        }

        $configuration = Arrays::arrayMergeRecursiveOverrule($configuration, $siteSpecificNodeTypeConfiguration);
    }
}
