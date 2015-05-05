<?php

/**
 * This formatter modifies data, so that it is in the same format as in get_browser()
 * result - all keys are lower case, values are assigned correct types.
 *
 * @author xemlock
 */
class ZeframBrowscap_Formatter extends \Crossjoin\Browscap\Formatter\AbstractFormatter
{
    /**
     * @var array
     */
    protected $_settings = array(
        // properties set during parsing of the browscap INI file
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
            $this->_settings[strtolower($key)] = $this->_coerceValue($value);
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
     * @param mixed $value
     * @return mixed
     */
    protected function _coerceValue($value)
    {
        if (is_string($value)) {
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

            // convert to int type only integral (digit-only) values to avoid garbling of version numbers
            if (is_string($value) && ctype_digit($value)) {
                /** @noinspection PhpWrongStringConcatenationInspection */
                $value = 0 + $value;
            }
        }

        return $value;
    }
}