<?php

namespace Database\Factories;

use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locale>
 */
class LocaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Locale::class;
    public function definition() {
        return [
            'code' => $this->faker->unique()->languageCode,
            'name' => $this->faker->randomElement(['English', 'French', 'Spanish', 'German', 'Chinese'])
        ];
    }
}
