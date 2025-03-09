<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;
    protected static $counter = 1; // Unique Counter

    public function definition()
    {
        return [
            'locale_id' => Locale::inRandomOrder()->first()->id ?? Locale::factory()->create()->id,
            'key' => 'key_' . self::$counter++, // Unique key without faker's unique()
            'content' => $this->faker->sentence,
            'tags' => json_encode([$this->faker->word]),
        ];
    }
}
