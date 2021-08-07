<?php

class RegisTest extends TestCase
{

    public function testSuccess()
    {
        $now = time();
        $this->json('POST', '/api/register', [
            'name' => 'alfiankan',
            'email' => 'alfianf'.$now.'@alfian.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'phone' => '5375489457'
        ])->seeJson(['message' => 'CREATED']);
    }

    public function testValidasi()
    {
        $now = time()+1;
        $response = $this->call('POST', '/api/register', [
            'email' => 'alfianf'.$now.'@alfian.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'phone' => '5375489457'
        ]);
        $this->assertNotEquals(201, $response->status());
    }
}