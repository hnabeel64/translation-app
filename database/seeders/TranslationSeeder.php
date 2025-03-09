<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least one locale exists
        $locales = Locale::pluck('id')->toArray();
        if (empty($locales)) {
            $locale = Locale::factory()->create();
            $locales = [$locale->id];
        }

        $totalRecords = 100000;
        $batchSize = 5000; // Batch size for insertion
        $translations = [];

        // Disable query log for performance
        DB::disableQueryLog();

        $start = microtime(true);

        // Build an array of 100,000 records
        for ($i = 0; $i < $totalRecords; $i++) {
            $translations[] = [
                'locale_id'  => $locales[array_rand($locales)],
                'key'        => 'key_' . $i,
                'content'    => 'Sample content ' . $i,
                'tags'       => json_encode(['tag_' . rand(1, 10)]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in batches using array_chunk
        foreach (array_chunk($translations, $batchSize) as $chunk) {
            DB::table('translations')->insert($chunk);
        }

        $end = microtime(true);
        $executionTime = ($end - $start) * 1000;
        echo "Inserted $totalRecords records in " . $executionTime . " ms.\n";
    }
}
