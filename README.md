# PunktDe.SiteSpecifics

In Neos you can serve multiple sites based on different site packages within the same Neos instance. While the frontend rendering can be completely separated per package, the configuration in Neos is global, so the same for all site packages.


This package aims to make configuration adjustements for the Neos backend per site to serve the most common multi-site use cases.

***Caution: This package might add some powerful options to adjust configuration site-specific but please be cautions and test the result Thoroughly.***

## Installation

```
composer require punktde/sitespecifics
```

## Features

### Adjust NodeType configuration

You can adjust node type configuration to adjust the backend view. For example, to show / hide a node type in the creation dialog when editing the site `yourSiteName` add to your Settings.yaml :

```  
PunktDe:
  SiteSpecifics:
    yourSiteName:
      nodeTypes:
        'Your.Vendor:Content.Headline':
          ui:
            group: not-shown
```

### Strip down the backend dimension selector

If a site, eg some microsite on the same instance, should not have all the dimensions of the main site, you can strip down the dimension selector in the backend. This removes the defined dimension ``de`` from the dimension selector.

This only affects the dimension selector, not the dimension configuration of the Neos content repository itself. That means you should only use this override feature to hide options or restrict combinations.   

**Example 1 - remove a preset completely: **

```  
PunktDe:
  SiteSpecifics:
    yourSiteName:
      dimensionSelector:
        language:
          presets:
            de: ~
```

**Example 2 - disallow a combination: **

```
PunktDe:
  SiteSpecifics:
    yourSiteName:
      dimensionSelector:
        country:
          presets:
            deu:
              constraints:
                language:
                  'en': false
```
