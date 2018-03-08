<?php

use App\User;
use App\Admin;
use App\Engagement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    Storage::fake('public');

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'fb_id' => str_random(10),
        'password' => $password ?: $password = bcrypt('secret'),
        'gender' => $faker->randomElement(['male', 'female']),
        'profile_pic' => UploadedFile::fake()->image('avatar.png'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Admin::class, function (Faker\Generator $faker) {

    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Engagement::class, function (Faker\Generator $faker) {
    
    Storage::fake('public');

    $groom = factory(User::class)->create([
        'gender' => 'male',
    ]);

    return  [
        'groom_id' => $groom->id,
        'creator_id' => $groom->id,
        'proposal_plan' => $faker->sentence,
        'proposal_date' => $faker->date('Y-m-d'),
        'culture' => $faker->randomElement([0, 1, 2, 3]),
        'image' => UploadedFile::fake()->image('image.png'),
        'proposal_lat' => $faker->randomFloat,
        'proposal_lng' => $faker->randomFloat,
        'proposal_place' => $faker->word,
        'status' => config('const.engagement.status.pending'),
        'phrase' => $faker->paragraph,
        'privacy' => $faker->randomElement([0, 1, 2]),
    ];

});

$factory->state(App\Engagement::class, 'default', function (Faker\Generator $faker) {
    // another of society's presumptions ...
    $bride = factory(User::class)->create([
        'gender' => 'female',
    ]); 

    return [
        'bride_id' => $bride->id,
    ];
});

$factory->state(App\Engagement::class, 'surprise', function (Faker\Generator $faker) {
    return [
        'is_surprise' => true,
        'surprise_other' => $faker->firstNameFemale, /* better be */
    ];
});
