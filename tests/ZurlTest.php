<?php

namespace ZengineOrg\Zurl\Tests;

use ZengineOrg\Zurl\Zurl;
use Orchestra\Testbench\TestCase;

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
