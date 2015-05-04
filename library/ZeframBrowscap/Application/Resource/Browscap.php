<?php

/**
 * Initializer for Browscap resource
 *
 * @author xemlock
 */
class ZeframBrowscap_Application_Resource_Browscap
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var ZeframBrowscap_Browscap
     */
    protected $_browscap;

    /**
     * Returns browscap instance
     *
     * @return ZeframBrowscap_Browscap
     */
    public function init()
    {
        return $this->getBrowscap();
    }

    /**
     * Creates and initialized a Browscap instance
     *
     * @return ZeframBrowscap_Browscap
     */
    public function getBrowscap()
    {
        if ($this->_browscap === null) {
            $this->_browscap = new ZeframBrowscap_Browscap($this->getOptions());
        }
        return $this->_browscap;
    }
}
