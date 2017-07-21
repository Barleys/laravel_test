<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->assertTrue(true);
    }

    public function testQueryOneUserOk()
    {
        $this->visit('/api/v1/user/query/id/1')
            ->seeJson([
                "id" =>  1,
                "name" => "admin",
                "email" => "",
                "created_at" => "2017-07-21 02:27:42",
                "updated_at" => "2017-07-21 05:28:51"
            ]);
    }

    public function testQueryOneUserFail()
    {
        $response = $this->call('GET', '/api/v1/user/query/id/199');
        $this->assertEquals(500, $response->status());
        $this->assertEquals('{"message":"No query results for model [App\\\User].","status_code":500}', $response->content());
    }

//    public function testSignupOk()
//    {
//        $this->post('api/v1/user/signup', ['name'=>'admins', 'password'=> 'admins'])
//            ->seeJson([
//
//                    "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjM4LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2xhcmEvcHVibGljL2luZGV4LnBocC9hcGkvdjEvdXNlci9zaWdudXAiLCJpYXQiOjE1MDA2MjIzMDYsImV4cCI6MTUwMDYyNTkwNiwibmJmIjoxNTAwNjIyMzA2LCJqdGkiOiJNdXBlZklleDJ0T2F0Vkc3In0.LZ5t5KDKvvY5_936Givp8SH1j7bajx8mwkDk19SU3_Y",
//
//            ]);
//    }

    public function testSignupFail()
    {
        $this->post('api/v1/user/signup', ['name'=>'admins2', 'password'=> 'admins'])
            ->seeJson([
                "message"=> "Could not create new user.",
                "errors"=> [
                    "name"=> [
                        "The name may only contain letters."
                    ]
                ],
                "status_code"=> 422
        ]);
    }

    public function testTest()
    {
        $this->visit('api/v1/user/test')
             ->seeJson([
                 'a' => 'b',
             ]);
    }

    public function testAuthOk()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbGFyYS9wdWJsaWMvaW5kZXgucGhwL2FwaS92MS91c2VyL2xvZ2luIiwiaWF0IjoxNTAwNjI0NDQwLCJleHAiOjE1MDA2MjgwNDAsIm5iZiI6MTUwMDYyNDQ0MCwianRpIjoiTnlwY056UTZxYlM4c1J0YSJ9.XymQDO9na3T1P_ECW9dDkmLmmGzg7_8cJm2_9QcMwFc';

        header('Authorization: Bearer ' . $token);
        $this->post('api/v1/user/signup')
             ->seeJson([
                 "id" =>  1,
                 "name" => "admin",
                 "email" => "",
                 "created_at" => "2017-07-21 02:27:42",
                 "updated_at" => "2017-07-21 05:28:51"
             ]);
    }
}



























