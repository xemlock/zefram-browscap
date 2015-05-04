# zefram-browscap

Add to your `application.ini`:

    includePaths.ZeframBrowscap = "/path/to/zefram-browscap"
    pluginPaths.ZeframBrowscap_Application_Resource_ = "ZeframBrowscap/Application/Resource/"

    resources.browscap.cacheDir = "/path/to/cache/dir"
    resources.browscap.datasetType = "default"

To retrieve browser information use:

    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
    $browser = $bootstrap->getResource('browscap')->getBrowser();

