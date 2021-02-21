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

class SiteSpecificNodeTypePostProcessor implements NodeTypePostprocessorInterface
{
    protected static ?string $siteName = null;

    /**
     * @Flow\InjectConfiguration(package="PunktDe.SiteSpecifics")
     * @var array
     */
    protected $siteSpecificConfiguration;

    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        $siteSpecificNodeTypeConfiguration = $this->siteSpecificConfiguration[$this->getCurrentSiteName()]['nodeTypes'][$nodeType->getName()] ?? null;

        if ($siteSpecificNodeTypeConfiguration === null) {
            return;
        }

        $configuration = Arrays::arrayMergeRecursiveOverrule($configuration, $siteSpecificNodeTypeConfiguration);
    }


    private function getCurrentSiteName(): string
    {
        if (self::$siteName === null) {
            $httpRequest = ServerRequest::fromGlobals();
            self::$siteName = $httpRequest->getQueryParams()['site'] ?? '';
        }

        return self::$siteName;
    }
}
