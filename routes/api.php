<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // user
    $prefix = 'App\Http\Controllers\UserController@';
    //$api->post('user/register_fb',              $prefix.'registerFB');
    $api->post('user/login',                    $prefix.'login');
    $api->post('user/register',                 $prefix.'register');
    $api->post('user/login_fb',                 $prefix.'loginFB');
    $api->post('user/get_by_id',                $prefix.'getByID');
    $api->post('user/search_paged',             $prefix.'searchUsers');
    $api->post('user/refresh_token',            $prefix.'refreshToken');
    $api->post('user/get_by_contact',           $prefix.'getByContact');
    // wedding
    $prefix = 'App\Http\Controllers\WeddingController@';
    $api->post('wedding/get_one',               $prefix.'getOther');
    $api->post('wedding/get_paged',             $prefix.'getPaginated');
    // engagement
    $prefix = 'App\Http\Controllers\EngagementController@';
    $api->post('engagement/get_one',            $prefix.'getOther');
    $api->post('engagement/get_paged',          $prefix.'getPaginated');
    // inspirations
    $prefix = 'App\Http\Controllers\InspirationController@';
    $api->post('inspiration/get_one',           $prefix.'getOther');
    $api->post('inspiration/get_paged',         $prefix.'getPaginated');
    // vendors
    $prefix = 'App\Http\Controllers\VendorController@';
    $api->post('vendor/get_one',                $prefix.'getOne');
    $api->post('vendor/get_paged',              $prefix.'getPaginated');
    $api->post('vendor_category/get_one',       $prefix.'getOneCategory');
    $api->post('vendor_category/get_paged',     $prefix.'getPaginatedCategories');
    // information
    $prefix = 'App\Http\Controllers\InformationController@';
    $api->post('information/about',             $prefix.'about');
    $api->post('information/terms',             $prefix.'termsAndConditions');
    $api->post('information/policy',            $prefix.'privacyPolicy');

});

$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
    // user
    $prefix = 'App\Http\Controllers\UserController@';
    $api->post('user/get',                      $prefix.'get');
    $api->post('user/update',                   $prefix.'update');
    $api->post('user/logout',                   $prefix.'logout');
    $api->post('user/close_account',            $prefix.'closeAccount');
    // wedding
    $prefix = 'App\Http\Controllers\WeddingController@';
    $api->post('wedding/create',                $prefix.'create');
    $api->post('wedding/update',                $prefix.'update');
    $api->post('wedding/get',                   $prefix.'get');
    $api->post('wedding/close',                 $prefix.'close');
    $api->post('wedding/comment/create',        $prefix.'createComment');
    $api->post('wedding/comment/update',        $prefix.'updateComment');
    $api->post('wedding/comment/get_paged',     $prefix.'getCommentsPaginated');
    $api->post('wedding/comment/delete',        $prefix.'deleteComment');
    $api->post('wedding/like/create',           $prefix.'createLike');
    $api->post('wedding/like/delete',           $prefix.'deleteLike');
    $api->post('wedding/photo/save',            $prefix.'savePhoto');
    $api->post('wedding/photo/authorize',       $prefix.'authorizePhoto');
    $api->post('wedding/photo/authorized',      $prefix.'getAuthorizedPhotos');
    $api->post('wedding/photo/unauthorized',    $prefix.'getUnauthorizedPhotos');
    $api->post('wedding/photo/delete',          $prefix.'deletePhoto');
    // engagement
    $prefix = 'App\Http\Controllers\EngagementController@';
    $api->post('engagement/create',             $prefix.'create');
    $api->post('engagement/update',             $prefix.'update');
    $api->post('engagement/get',                $prefix.'get');
    $api->post('engagement/close',              $prefix.'close');
    $api->post('engagement/comment/create',     $prefix.'createComment');
    $api->post('engagement/comment/update',     $prefix.'updateComment');
    $api->post('engagement/comment/get_paged',  $prefix.'getCommentsPaginated');
    $api->post('engagement/comment/delete',     $prefix.'deleteComment');
    $api->post('engagement/like/create',        $prefix.'createLike');
    $api->post('engagement/like/delete',        $prefix.'deleteLike');
    $api->post('engagement/photo/save',         $prefix.'savePhoto');
    $api->post('engagement/photo/authorize',    $prefix.'authorizePhoto');
    $api->post('engagement/photo/authorized',   $prefix.'getAuthorizedPhotos');
    $api->post('engagement/photo/unauthorized', $prefix.'getUnauthorizedPhotos');
    $api->post('engagement/photo/delete',       $prefix.'deletePhoto');
    // inspiration
    $prefix = 'App\Http\Controllers\InspirationController@';
    $api->post('inspiration/create',            $prefix.'create');
    $api->post('inspiration/update',            $prefix.'update');
    $api->post('inspiration/delete',            $prefix.'delete');
    $api->post('inspiration/comment/create',    $prefix.'createComment');
    $api->post('inspiration/comment/update',    $prefix.'updateComment');
    $api->post('inspiration/comment/delete',    $prefix.'deleteComment');
    $api->post('inspiration/like/create',       $prefix.'createLike');
    $api->post('inspiration/like/delete',       $prefix.'deleteLike');
    // invitation
    $prefix = 'App\Http\Controllers\InvitationController@';
    $api->post('invitation/create',             $prefix.'create');
    $api->post('invitation/delete',             $prefix.'delete');
    $api->post('invitation/secure',             $prefix.'secureInvitation');
    $api->post('invitation/user_invitations',   $prefix.'userInvitations');
    $api->post('invitation/wedding_invitations',$prefix.'weddingInvitations');
});
