<?php

namespace Zengine\Zurl\Tests;

use Zengine\Zurl\Zurl;
use Orchestra\Testbench\TestCase;

class ZurlTest extends TestCase
{
    public function test_base_get_request()
    {
        $zurl = new Zurl();

        $zurl->get('https://jsonplaceholder.typicode.com/todos/1');

        $response = $zurl->execute();
        $this->assertFalse($response->failed());
        $zurl->close();
    }

    public function test_post_request()
    {
        $zurl = new Zurl();
        $zurl->post('https://jsonplaceholder.typicode.com/todos', []);

        $response = $zurl->execute();

        $this->assertArrayHasKey('http_code', $response->getResponse());
        $this->assertArrayHasKey('body', $response->getResponse());
        $this->assertArrayHasKey('headers', $response->getResponse());
        $this->assertArrayHasKey('total_time', $response->getResponse());
        $this->assertFalse($response->failed());
        $zurl->close();
    }

    public function test_put_request()
    {
        $zurl = new Zurl();
        $zurl->put('https://jsonplaceholder.typicode.com/posts/1', ['title' => 'New todo']);

        $response = $zurl->execute();

        $this->assertFalse($response->failed());
        $zurl->close();
    }

    public function test_patch_request()
    {
        $zurl = new Zurl();
        $zurl->patch('https://jsonplaceholder.typicode.com/posts/1', ['title' => 'New todo']);

        $response = $zurl->execute();

        $this->assertFalse($response->failed());
        $zurl->close();
    }

    public function test_delete_request()
    {
        $zurl = new Zurl();
        $zurl->delete('https://jsonplaceholder.typicode.com/posts/1');

        $response = $zurl->execute();

        $this->assertFalse($response->failed());
        $zurl->close();
    }
}
