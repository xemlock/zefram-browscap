# zefram-browscap

Browser capability detection for Zend Framework 1. This library is a wrapper for
[crossjoin/browscap](https://packagist.org/packages/crossjoin/browscap) library.

Add to your `application.ini`:

    includePaths.ZeframBrowscap = "/path/to/zefram-browscap"
    pluginPaths.ZeframBrowscap_Application_Resource_ = "ZeframBrowscap/Application/Resource/"

    resources.browscap.cacheDir = "/path/to/cache/dir"
    resources.browscap.datasetType = "default"
    resources.browscap.autoUpdate = TRUE

To retrieve browser information use:

    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
    $browser = $bootstrap->getResource('browscap')->getBrowser();

