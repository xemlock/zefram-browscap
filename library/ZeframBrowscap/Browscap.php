<?php

/**
 * Wrapper for Crossjoin\Browscap which encapsulates its configuration
 *
 * @uses   \Crossjoin\Browscap
 * @author xemlock
 */
class ZeframBrowscap_Browscap
{
    const DATASET_TYPE_SMALL    = 'small';
    const DATASET_TYPE_DEFAULT  = 'default';
    const DATASET_TYPE_LARGE    = 'large';

    /**
     * @var string
     */
    protected $_cacheDir;

    /** 
     * @var string
     */
    protected $_datasetType = self::DATASET_TYPE_DEFAULT;

    /**
     * @var bool
     */
    protected $_autoUpdate = true;

    /** 
     * @var \Crossjoin\Browscap\Formatter\AbstractFormatter
     */
    protected $_formatter;

    /**
     * @var \Crossjoin\Browscap\Updater\AbstractUpdater
     */
    protected $_updater;

    /**
     * @var \Crossjoin\Browscap\Browscap
     */
    protected $_browscap;

    /**
     * @param  array $options OPTIONAL
     * @return void
     */
    public function __construct(array $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        if ($this->_cacheDir === null) {
            $this->setCacheDir(sys_get_temp_dir());
        }
        return $this->_cacheDir;
    }

    /**
     * @param  string $cacheDir
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setCacheDir($cacheDir)
    {
        if (!is_dir($cacheDir) || !is_writable($cacheDir)) {
            throw new InvalidArgumentException(sprintf('Cache directory "%s" is not readable', $cacheDir));
        }
        $this->_cacheDir = $cacheDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatasetType()
    {
        return $this->_datasetType;
    }

    /**
     * @param string $datasetType
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setDatasetType($datasetType)
    {
        switch ($datasetType) {
            case self::DATASET_TYPE_SMALL:
            case self::DATASET_TYPE_DEFAULT:
            case self::DATASET_TYPE_LARGE:
                break;

            default:
                throw new InvalidArgumentException('Invalid datasetType value');
        }
        $this->_datasetType = $datasetType;
        return $this;
    }

    /**
     * @param bool $autoUpdate
     * @return $this
     */
    public function setAutoUpdate($autoUpdate)
    {
        $this->_autoUpdate = $autoUpdate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAutoUpdate()
    {
        return $this->_autoUpdate;
    }

    /**
     * @return \Crossjoin\Browscap\Formatter\AbstractFormatter
     */
    public function getFormatter()
    {
        if ($this->_formatter === null) {
            $this->setFormatter(new ZeframBrowscap_Formatter());
        }
        return $this->_formatter;
    }

    /**
     * @param  \Crossjoin\Browscap\Formatter\AbstractFormatter $formatter
     * @return $this
     */
    public function setFormatter(\Crossjoin\Browscap\Formatter\AbstractFormatter $formatter)
    {
        $this->_formatter = $formatter;
        return $this;
    }

    /**
     * @return \Crossjoin\Browscap\Updater\AbstractUpdater
     * @throws RuntimeException
     */
    public function getUpdater()
    {
        if ($this->_updater === null) {
            $updater = \Crossjoin\Browscap\Updater\FactoryUpdater::getInstance();
            if (!$updater) {
                throw new RuntimeException('Unable to create an updater instance');
            }
            $this->_updater = $updater;
        }
        return $this->_updater;
    }

    /**
     * @return \Crossjoin\Browscap\Browscap
     */
    public function getBrowscap()
    {
        if ($this->_browscap === null) {
            $this->_browscap = new \Crossjoin\Browscap\Browscap();
        }
        return $this->_browscap;
    }

    /**
     * @param string|null $userAgent OPTIONAL
     * @param bool $returnArray OPTIONAL
     * @return mixed
     */
    public function getBrowser($userAgent = null, $returnArray = false)
    {
        $browscap = $this->getBrowscap();
        $this->_setupBrowscapOptions();

        $data = $browscap->getBrowser($userAgent)->getData();
        return $returnArray ? $data : (object) $data;
    }

    /**
     * Triggers an update check (with the option to force an update)
     *
     * @param  bool $forceUpdate
     */
    public function update($forceUpdate = false)
    {
        $this->_setupBrowscapOptions($forceUpdate);
        \Crossjoin\Browscap\Browscap::update($forceUpdate);
    }

    /**
     * Updates global \Crossjoin\Browscap confguration according to local configuration
     *
     * @param bool $forceUpdate OPTIONAL
     * @return void
     */
    protected function _setupBrowscapOptions($forceUpdate = false)
    {
        \Crossjoin\Browscap\Cache\File::setCacheDirectory($this->getCacheDir());
        \Crossjoin\Browscap\Browscap::setFormatter($this->getFormatter());

        if ($forceUpdate || $this->getAutoUpdate()) {
            $updater = $this->getUpdater();
        } else {
            $updater = new \Crossjoin\Browscap\Updater\None();
        }
        Crossjoin\Browscap\Browscap::setUpdater($updater);
        
        switch ($this->getDatasetType()) {
            case self::DATASET_TYPE_SMALL:
                $datasetType = \Crossjoin\Browscap\Browscap::DATASET_TYPE_SMALL;
                break;

            case self::DATASET_TYPE_DEFAULT:
                $datasetType = \Crossjoin\Browscap\Browscap::DATASET_TYPE_DEFAULT;
                break;

            case self::DATASET_TYPE_LARGE:
                $datasetType = \Crossjoin\Browscap\Browscap::DATASET_TYPE_LARGE;
                break;

            default:
                $datasetType = null;
                break;
        }
        \Crossjoin\Browscap\Browscap::setDatasetType($datasetType);
    }
}
