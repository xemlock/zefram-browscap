<?php

class ZeframBrowscap_Formatter extends \Crossjoin\Browscap\Formatter\AbstractFormatter
{
    /**
     * @var array
     */
    protected $_settings = array(
        // properties set during parsing browscap INI file
        'browser_name_regex'          => null,
        'browser_name_pattern'        => null,
        'Parent'                      => null,

        // small dataset properties
        'Comment'                     => null,
        'Browser'                     => null,
        'Version'                     => null,
        'Platform'                    => null,
        'isMobileDevice'              => null,
        'isTablet'                    => null,
        'Device_Type'                 => null,

        // additional properties provided by default dataset
        'Browser_Maker'               => null,
        'MajorVer'                    => null,
        'MinorVer'                    => null,
        'Win32'                       => null,
        'Win64'                       => null,
        'Device_Pointing_Method'      => null,

        // additional properties provided by large dataset
        'Browser_Type'                => null,
        'Browser_Bits'                => null,
        'Browser_Modus'               => null,
        'Platform_Version'            => null,
        'Platform_Description'        => null,
        'Platform_Bits'               => null,
        'Platform_Maker'              => null,
        'Alpha'                       => null,
        'Beta'                        => null,
        'Win16'                       => null,
        'Frames'                      => null,
        'IFrames'                     => null,
        'Tables'                      => null,
        'Cookies'                     => null,
        'BackgroundSounds'            => null,
        'JavaScript'                  => null,
        'VBScript'                    => null,
        'JavaApplets'                 => null,
        'ActiveXControls'             => null,
        'isSyndicationReader'         => null,
        'Crawler'                     => null,
        'CssVersion'                  => null,
        'AolVersion'                  => null,
        'Device_Name'                 => null,
        'Device_Maker'                => null,
        'Device_Code_Name'            => null,
        'Device_Brand_Name'           => null,
        'RenderingEngine_Name'        => null,
        'RenderingEngine_Version'     => null,
        'RenderingEngine_Description' => null,
        'RenderingEngine_Maker'       => null,
    );

    /**
     * @param  array $data
     * @return void
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {
            switch (strtolower($value)) {
                case 'unknown':
                    $value = null;
                    break;

                case 'true':
                    $value = true;
                    break;

                case 'false':
                    $value = false;
                    break;
            }

            // convert to int only integer values, otherwise version numers
            // will be garbled
            if (is_string($value) && ctype_digit($value)) {
                $value = 0 + $value;
            }

            $this->_settings[$key] = $value;
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_settings;
    }

    /**
     * @param  string
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->_settings[$key]) ? $this->_settings[$key] : null;
    }

    /**
     * @param  string
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->_settings[$key]);
    }
}
