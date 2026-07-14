<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserRequest;
use Faker\Generator as Faker;


$factory->define(userRequest::class, function (Faker $faker) {

    $deptOptions = ['Child and Welfare Dept', 'Agriculture Dept', 'Transport Dept'];
    $genderOptions = ['Male', 'Female', 'Other'];
    $stateOptions = ['Goa','Maharastra','Asssam', 'Orrisa', 'Gujrat', 'Kerela', 'Tamil Nadu', 'Madhya Pradesh', 'Rajasthan','Karnataka'];

    return [
        'applied_dept' => $faker->randomElement($deptOptions),
        'name' => $faker->text,
        'gender' => $faker->randomElement($genderOptions),
        'address_line1' => $faker->text,
        'address_line2' => $faker->text,
        'address_line3' => $faker->text,
        'pincode' => $faker->numberBetween(100000, 999999),
        'country' => 'India',
        'state' => $faker->randomElement($stateOptions),
        'district' => $faker->text,
        'edu_status' => $faker->randomElement(['educated', 'not educated']),
        'phone_no' => $faker->phoneNumber,
        'mobile_no' => $faker->e164PhoneNumber,
        'email_id' => $faker->email,
        'is_Indian' => 'Yes',
        'is_bpl' => $faker->randomElement(['yes','no']),
        'card_no' => $faker->numberBetween(10000000, 99999999),
        'issuing_authority' => 'Testing authority',
        'request_text' => $faker->paragraph,
        'document' => $faker->file,
        'issued_year' => $faker->numberBetween(2019, 2023),
    ];
});

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\user_request>
 */
class UserRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
