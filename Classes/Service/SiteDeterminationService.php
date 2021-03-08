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

        if (array_key_exists('site', $httpRequest->getQueryParams())) {
            self::$siteName = $httpRequest->getQueryParams()['site'] ?? '';
        }

        return self::$siteName;
    }
}
