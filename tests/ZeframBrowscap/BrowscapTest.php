<?php

class ZeframBrowscap_BrowscapTest extends PHPUnit_Framework_TestCase
{
    public function testResult()
    {
        $browscap = new ZeframBrowscap_Browscap();
        $browscap->setDatasetType('default');
        $browscap->setAutoUpdate(true);

        $result = $browscap->getBrowser('Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0');

        $this->assertInstanceOf('stdClass', $result);
        $this->assertEquals('36.0', $result->version);

        if ($browscap->getDatasetType() !== 'small') {
            $this->assertEquals('36', $result->majorver);
        }

        $this->assertEquals('', $result->ismobiledevice);

        $result = $browscap->getBrowser('Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0', true);
        $this->assertInternalType('array', $result);
    }
}