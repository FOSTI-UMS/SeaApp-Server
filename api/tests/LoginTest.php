<?php

class LoginTest extends TestCase
{

    public function testBasicExample()
    {
        $response = $this->call('POST', '/api/login', [
            'email' => 'alfiankan19k@gmail.com',
            'password' => '12345'
        ]);
        echo json_encode($response);
        //$this->assertEquals(201,$response->status);
    }
}