<?php

namespace ZengineOrg\Zurl\Tests;

use Orchestra\Testbench\TestCase;
use ZengineOrg\Zurl\Zurl;

class ZurlTest extends TestCase
{
    public function test_base_get_request()
    {
        $zurl = new Zurl();

        $zurl->get('https://zengine.org');

        $response = $zurl->execute();

        $this->assertFalse($response->failed());
    }
}
