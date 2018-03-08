<?php

namespace Tests\Feature;

use App\User;
use App\Engagement;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EngagementTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCannotCreateEngagementWithoutAuth()
    {
        $eng = factory(Engagement::class)->states('default')->make()->toArray();
        $this->json('POST', 'api/engagement/create', $eng)->assertJson([
            'error' => true,
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);

    }

    public function testCannotCreateEngagementWithoutBrideAndGroom()
    {
        $eng = factory(Engagement::class)->states('default')->make([
            'surprise_other' => '',
        ])->toArray();
        $authData = $this->logInUser($eng['groom_id']);
        unset($eng['bride_id']);
        unset($eng['groom_id']);
        $this->json('POST', 'api/engagement/create', $eng, $authData['header'])->assertJson([
            'error' => true,
        ])->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    public function testCanCreateSurpriseEngagement()
    {
        $eng = factory(Engagement::class)->states('surprise')->make()->toArray();
        $authData = $this->logInUser($eng['groom_id']);
        $this->json('POST', 'api/engagement/create', $eng, $authData['header'])->assertJson([
            'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }

    public function testCanCreateEngagement()
    {
        $eng = factory(Engagement::class)->states('default')->make()->toArray();
        $authData = $this->logInUser($eng['groom_id']);
        $this->json('POST', 'api/engagement/create', $eng, $authData['header'])->assertJson([
            'error' => false,
        ])->assertStatus(Response::HTTP_OK);
    }
    
    private function holder()
    {
        $this->json('POST', 'api/engagement', [

        ])->assertJson([

        ])->assertStatus(Response::HTTP_);
    }

    private function logInUser($id)
    {
        $user = User::find($id)->makeVisible(['remember_token', 'password'])->toArray();
        $response = $this->json('POST', '/api/user/login', [
            'email' => $user['email'],
            'password' => 'secret',
        ]);

        $header = [
            'Authorization' => 'Bearer ' . $response->getOriginalContent()['token'],
            'Accept' => 'application/json',
        ];
        return [
            'user' => $user,
            'header' => $header,
        ];
    }
}
