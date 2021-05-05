<?php
declare(strict_types=1);

namespace PunktDe\SiteSpecifics\Service;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Psr7\ServerRequest;

class SiteDeterminationService
{
    public static ?string $siteName = null;

    public function getCurrentSiteName(): ?string
    {
        if (self::$siteName !== null) {
            return self::$siteName;
        }

        $httpRequest = ServerRequest::fromGlobals();

        /*
         * Site as query parameter.
         * Used when querying adjusted nodeTypes
         */
        if (array_key_exists('site', $httpRequest->getQueryParams())) {
            self::$siteName = $httpRequest->getQueryParams()['site'] ?? '';
        }

        /*
         * Site as uri path segment
         * Used to get dimension constraints in the backend
         */
        preg_match('/content-dimensions\/([a-zA-Z\-_.]+)\/[a-zA-Z\-_]+\.json/', $httpRequest->getUri()->getPath(), $matches, PREG_OFFSET_CAPTURE);
        if (($matches[1] ?? null) !== null) {
            self::$siteName = $matches[1][0];
            return self::$siteName;
        }

        return self::$siteName;
    }
}
