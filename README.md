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
punktDe:
  SiteSpecifics:
    yourSiteName:
      nodeTypes:
        'Your.Vendor:Content.Headline':
          ui:
            group: not-shown
```