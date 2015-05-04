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
     * @var \Crossjoin\Browscap\Formatter\AbstractFormatter
     */
    protected $_formatter;

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
     * @return $string
     */
    public function getDatasetType()
    {
        return $this->_datasetType;
    }

    /**
     * @param  string
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
     */
    public function setFormatter(\Crossjoin\Browscap\Formatter\AbstractFormatter $formatter)
    {
        $this->_formatter = $formatter;
        return $this;
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
     * @param  string $userAgent
     * @param  bool $returnArray
     * @return object|array
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
        $this->_setupBrowscapOptions();
        \Crossjoin\Browscap\Browscap::update($forceUpdate);
    }

    /** 
     * Updates global \Crossjoin\Browscap confguration according to local configuration
     *
     * @return void
     */
    protected function _setupBrowscapOptions()
    {
        \Crossjoin\Browscap\Cache\File::setCacheDirectory($this->getCacheDir());
        \Crossjoin\Browscap\Browscap::setFormatter($this->getFormatter());
        
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
        }
        \Crossjoin\Browscap\Browscap::setDatasetType($datasetType);
    }
}
