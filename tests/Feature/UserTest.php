<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * A basic test example
     *
     * @return void
     */
    public function testCannotCreateUserWithoutName()
    {
        $user = factory(User::class)->make([
            'name' => '',
        ])->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/register', $user)
        ->assertJson([
             'error' => true,
             'error-field' => 'name',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Email required
     *
     * @return void
     */
    public function testCannotCreateUserWithoutEmail()
    {
        $user = factory(User::class)->make([
            'email' => '',
        ])->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/register', $user)
        ->assertJson([
             'error' => true,
             'error-field' => 'email',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Gender Required
     *
     * @return void
     */
    public function testCannotCreateUserWithoutGender()
    {
        $user = factory(User::class)->make([
            'gender' => '',
        ])->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/register', $user)
        ->assertJson([
             'error' => true,
             'error-field' => 'gender',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Profile Picture Image
     *
     * @return void
     */
    public function testProfilePictureMustBeImage()
    {
        $user = factory(User::class)->make([
            'profile_pic' => '',
        ])->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/register', $user)
        ->assertJson([
             'error' => true,
             'error-field' => 'profile_pic'
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Can Create User
     *
     * @return void
     */
    public function testCanCreateUser()
    {
        $user = factory(User::class)->make()->makeVisible(['remember_token','password'])->toArray();
        $response = $this->json('POST', '/api/user/register', [
            'email' => $user['email'],
            'password' => 'secret',
            'gender' => $user['gender'],
            'name' => $user['name'],
        ])->assertJson([
            'error' => false,
        ])->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
    }


    /**
     * Cannot Login Without Email
     *
     * @return void
     */
    public function testCannotLoginWithoutEmail()
    {
        $user = factory(User::class)->create();
        $user->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/login', [
            'email' => '',
        ])->assertJson([
              'error' => true,
              'error-field' => 'email'
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Can login with email
     *
     * @return void
     */
    public function testCanLogin()
    {
        $user = factory(User::class)->create([
            'remember_token' => ''
        ])->makeVisible(['remember_token','password'])->toArray();

        $this->json('POST', '/api/user/login', [
            'email' => $user['email'],
            'password' => 'secret',
        ])->assertJson([
              'error' => false
        ])->assertStatus(Response::HTTP_OK);
    }


    /**
     * Cannot login without facebook id
     *
     * @return void
     */
    public function testCannotLoginWithoutFacebookID()
    {
        $user = factory(User::class)->make();
        $this->json('POST', '/api/user/login_fb', [
            'fb_id' => '',
            'email' => $user['email'],
        ])->assertJson([
              'error' => true,
              'error-field' => 'fb_id',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Cannot login without email facebook
     *
     * @return void
     */
    public function testCannotLoginWithoutEmailFacebook()
    {
        $user = factory(User::class)->make();
        $this->json('POST', '/api/user/login_fb', [
            'name' => $user['name'],
            'fb_id' => $user['fb_id'],
            'phone' => '',
            'gender' => $user['gender'],
        ])->assertJson([
              'error' => true,
              'error-field' => 'phone',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Cannot login without gender facebook.
     *
     * @return void
     */
    public function testCannotLoginWithoutGenderFacebook()
    {
        $user = factory(User::class)->make();
        $this->json('POST', '/api/user/login_fb', [
            'name' => $user['name'],
            'fb_id' => $user['fb_id'],
            'email' => $user['email'],
            'gender' => '',
        ])->assertJson([
              'error' => true,
              'error-field' => 'gender',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Can login with Facebook
     *
     * @return void
     */
    public function testCanLoginWithFacebookID()
    {
        $user = factory(User::class)->make()->toArray();
        $this->json('POST', '/api/user/login_fb', [
            'name' => $user['name'],
            'fb_id' => $user['fb_id'],
            'gender' => $user['gender'],
            'email' => $user['email'],
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }

    /**
     * Can register with email and password but login with Facebook
     *
     * @return void
     */
    public function testCanRegisterWithEmailButLoginWithFB()
    {
        $user = factory(User::class)->make()->makeVisible(['remember_token','password'])->toArray();
        $user['password'] = 'secret';
        $this->json('POST', '/api/user/register', $user)
        ->assertJson([
            'error' => false,
        ])->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);

        $this->json('POST', '/api/user/login_fb', [
            'fb_id' => $user['fb_id'],
            'email' => $user['email'],
        ])->assertJson([
              'error' => false,
              'user' => [ 'email' => $user['email'] ],
        ])->assertStatus(Response::HTTP_OK);

    }

    /**
     * Cannot search with page but without count
     *
     * @return void
    public function testCannotSearchWithPageButWithoutCount()
    {
        $this->json('POST', '/api/user/search_paged', [
            'search_term' => 'e',
            'page' => 1,
        ])->assertJson([
              'error' => true,
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }
     */

    /**
     * Cannot search with count but without page
     *
     * @return void
     */
    public function testCannotSearchWithCountButWithoutPage()
    {
        $this->json('POST', '/api/user/search_paged', [
            'search_term' => 'e',
            'count' => 2,
        ])->assertJson([
              'error' => true,
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }


    /**
     * Cannot search without search term
     *
     * @return void
     */
    public function testCannotSearchWithoutSearchTerm()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'count' => 2,
            'gender' => 'both',
        ])->assertJson([
              'error' => true,
              'error-field' => 'search_term',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Cannot search without gender
     *
     * @return void
    public function testCannotSearchWithoutGender()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'count' => 1,
            'search_term' => 'e',
        ])->assertJson([
              'error' => true,
              'error-field' => 'gender',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }
     */


    /**
     * Cannot search without search_by
     *
     * @return void
    public function testCannotSearchWithoutSearchBy()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'search_term' => 'e',
        ])->assertJson([
              'error' => true,
              'error-field' => 'search_by',
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }
     */

    /**
     * Can search users with both phone and name.
     *
     * @return void
     */
    public function testCanSearchUsersWithBothPhoneAndName()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'count' => 1,
            'search_term' => 'e',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }


    /**
     * Can search users with gender
     *
     * @return void
     */
    public function testCanSearchUsersWithGender()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'search_term' => 'e',
            'gender' => 'male',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }


    /**
     * Can search users with name
     *
     * @return void
     */
    public function testCanSearchUsersWithName()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'search_term' => 'e',
            'gender' => 'female',
            'search_by' => 'name',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }

    /**
     * Can search users with phone
     *
     * @return void
     */
    public function testCanSearchUsersWithPhone()
    {
        $this->json('POST', '/api/user/search_paged', [
            'page' => 1,
            'search_term' => 'e',
            'gender' => 'female',
            'search_by' => 'phone',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }

    /**
     * Can refresh token
     *
     * @return void
     */
    public function testCanRefreshToken()
    {
        $user = factory(User::class)->create([
            'remember_token' => ''
        ])->makeVisible(['remember_token','password'])->toArray();

        $response = $this->json('POST', '/api/user/login', [
            'email' => $user['email'],
            'password' => 'secret',
        ]);

        $response = $this->json('POST', '/api/user/refresh_token', [
            'token' => $response->getOriginalContent()['token'],
        ], [
            'Authorization' => 'Bearer ' . $response->getOriginalContent()['token'],
            'Accept' => 'application/json',
        ])->assertJson([
            'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }


    /**
     * Can get user.
     *
     * @return void
     */
    public function testCanGetUserDetails()
    {
        $user = factory(User::class)->create([
            'remember_token' => ''
        ])->makeVisible(['remember_token','password'])->toArray();

        $response = $this->json('POST', '/api/user/login', [
            'email' => $user['email'],
            'password' => 'secret',
        ]);

        $this->json('POST', '/api/user/get', [  ], [
            'Authorization' => 'Bearer ' . $response->getOriginalContent()['token'],
            'Accept' => 'application/json',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }


    public function testCanUpdateUserDetails()
    {
        $user = factory(User::class)->create([
            'remember_token' => ''
        ])->makeVisible(['remember_token','password'])->toArray();

        $response = $this->json('POST', '/api/user/login', [
            'email' => $user['email'],
            'password' => 'secret',
        ]);

        $newUser = factory(User::class)->make()->makeVisible(['remember_token','password'])->toArray();
        $this->json('POST', '/api/user/update', $newUser, [
            'Authorization' => 'Bearer ' . $response->getOriginalContent()['token'],
            'Accept' => 'application/json',
        ])->assertJson([
              'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }

}
