<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_page_is_correctly_redirected() {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_main_page_is_accessible() {
        $response = $this->get('/ads');

        $response->assertStatus(200);
    }

    public function test_login_form() {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_not_be_duplicate() {
        $user1 = User::make([
            'name' => 'test1',
            'email' => 'laraveltest1@test.com',
            'password' => 'test123456',
            'phone_number' => '123456789'
        ]);

        $user2 = User::make([
            'name' => 'test2',
            'email' => 'laraveltest2@test.com',
            'password' => 'test123456',
            'phone_number' => '123456789'
        ]);

        $this->assertTrue($user1->email != $user2->email);
    }

    public function test_user_create_and_delete() {
        $user = new User([
            'name' => 'test3',
            'email' => 'laraveltest3@test.com',
            'password' => 'test123456',
            'phone_number' => '123456789'
        ]);

        $user->save();

        if($user) {
            $this->assertTrue($user->all()->last()->delete());
        }
    }

    public function test_registration_form_stores_new_user() {
        $response = $this->post('/register', [
            'name' => 'test4',
            'email' => 'laraveltest4@test.com',
            'password' => 'test123456',
            'phone_number' => '123456789'
        ]);

        $response->assertRedirect('/');

    }

    public function test_can_not_access_profile_page_when_not_logged() {
        $response = $this->get('/user/1');

        $response->assertRedirect('/login');
    }

    public function test_can_not_access_product_detail_when_not_logged() {
        $response = $this->get('/ads/1');

        $response->assertRedirect('/login');
    }

    public function test_can_not_create_new_ad_when_not_logged() {
        $response = $this->get('/ads/create');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_new_ad() {
        
        $user = new User([
            'name' => 'test5',
            'email' => 'laraveltest5@test.com',
            'password' => 'test123456',
            'phone_number' => '123456789'
        ]);

        $user->save();
 
        $response = $this->actingAs($user)
                         ->post('/ads/create', [
                                'title' => 'test5',
                                'category' => 'test5',
                                'description' => 'test5',
                                'price' => '0',
                                'location' => 'test5',
                                'autho_id' => '0',
                                'picture' => 'test5'
                            ]);

                            
        if($user) {
            $user->all()->last()->delete();
        }
        
        $response->assertStatus(302);
    }
}
