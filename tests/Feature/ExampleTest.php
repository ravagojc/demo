<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class ExampleTest extends TestCase
{
    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string|null                                $driver
     * @return $this
     */
    public function actingAs($user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);
        
        return $this;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_register_user()
    {
        $response = $this->postJson('api/register', ['email'=> 'joesef@youmama.com', 'password'=>'testpass']);

        $response
            ->assertStatus(201)
            ->assertJson(['success'=>true]);
    }
    public function test_email_exists()
    {
        $response = $this->postJson('api/register', ['email'=> 'joesef@youmama.com', 'password'=>'testpass']);

        $response
            ->assertStatus(400);
    }

    public function test_login_fail()
    {
        $response = $this->postJson('api/login', ['email'=> 'joesef@youmama.com', 'password'=>'testpasse']);

        $response
            ->assertStatus(400)
            ->assertJson(['success'=>false]);
    }

    public function test_login_fail_after_multiple_tries()
    {
        $x=1;
        do{
            $this->postJson('api/login', ['email'=> 'joesef@youmama.com', 'password'=>'testpasse']);
            $x++;
        }while($x<=5);

        $response = $this->postJson('api/login', ['email'=> 'joesef@youmama.com', 'password'=>'testpasse']);
        $response
            ->assertHeader('X-RateLimit-Limit')
            ->assertJson(['error'=>'Too many logins, try again after 5 minutes']);
    }

    public function test_login_success()
    {
        $response = $this->postJson('api/login', ['email'=> 'joesef@youmama.com', 'password'=>'testpass']);

        $response
            ->assertJson(['success'=>true]);
    }

    public function test_order_successful()
    {
        $user = User::where('email', 'joesef@youmama.com')->first();
        $response = $this->actingAs($user)
                        ->postJson('api/order', ['id'=> '2', 'quantity'=>'20']);

        $response
            ->assertJson(['success'=>true]);
    }

    public function test_order_unsuccessful()
    {
        $user = User::where('email', 'joesef@youmama.com')->first();
        $response = $this->actingAs($user)
                        ->postJson('api/order', ['id'=> '2', 'quantity'=>'20000']);

        $response
            ->assertJson(['success'=>false]);
    }
    

}
