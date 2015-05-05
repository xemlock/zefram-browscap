<?php

/**
 * This formatter modifies data, so that it is in the same format as in
 * get_browser() result - all keys are lower case, values are strings, however
 * boolean values are represented by '1' and '', instead of 'true' and 'false'
 * strings. Properties not present in browscap file are null.
 *
 * @author xemlock
 */
class ZeframBrowscap_Formatter extends \Crossjoin\Browscap\Formatter\AbstractFormatter
{
    /**
     * @var array
     */
    protected $_capabilityNames = array(
        // properties set during parsing of the browscap INI file
        'browser_name_regex',
        'browser_name_pattern',
        'Parent',

        // small dataset properties
        'Comment',
        'Browser',
        'Version',
        'Platform',
        'isMobileDevice',
        'isTablet',
        'Device_Type',

        // additional properties provided by default dataset
        'Browser_Maker',
        'MajorVer',
        'MinorVer',
        'Win32',
        'Win64',
        'Device_Pointing_Method',

        // additional properties provided by large dataset
        'Browser_Type',
        'Browser_Bits',
        'Browser_Modus',
        'Platform_Version',
        'Platform_Description',
        'Platform_Bits',
        'Platform_Maker',
        'Alpha',
        'Beta',
        'Win16',
        'Frames',
        'IFrames',
        'Tables',
        'Cookies',
        'BackgroundSounds',
        'JavaScript',
        'VBScript',
        'JavaApplets',
        'ActiveXControls',
        'isSyndicationReader',
        'Crawler',
        'CssVersion',
        'AolVersion',
        'Device_Name',
        'Device_Maker',
        'Device_Code_Name',
        'Device_Brand_Name',
        'RenderingEngine_Name',
        'RenderingEngine_Version',
        'RenderingEngine_Description',
        'RenderingEngine_Maker',
    );

    /**
     * @var array
     */
    protected $_settings;

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data = array())
    {
        $this->_settings = array();
        foreach ($data as $key => $value) {
            $this->_settings[strtolower($key)] = $this->_stringify($value);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        // lowercase default capability names, set all values to null
        $data = array_fill_keys(
            array_map('strtolower', $this->_capabilityNames),
            null
        );
        // add values provided by the parser
        if ($this->_settings) {
            $data = array_merge($data, $this->_settings);
        }
        return $data;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function _stringify($value)
    {
        switch (strtolower($value)) {
            case 'true':
                $value = true;
                break;

            case 'false':
                $value = false;
                break;
        }

        return (string) $value;
    }
}