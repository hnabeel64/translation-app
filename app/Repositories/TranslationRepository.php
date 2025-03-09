<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TranslationRepository
{
    public function create(array $data): Translation
    {
        $translation = Translation::create($data);
        Cache::forget('translations');
        return $translation;
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        Cache::forget('translations');
        return $translation;
    }

    public function delete(Translation $translation): bool
    {
        Cache::forget('translations');
        return $translation->delete();
    }

    public function search($locale, $key)
    {
        return Cache::remember("search_{$locale}_{$key}", 600, function () use ($locale, $key) {
            return Translation::whereHas('locale', fn($query) => $query->where('code', $locale))
                ->where('key', 'like', "%{$key}%")
                ->limit(1000)
                ->get();
        });
    }

    public function exportToJson(): JsonResponse
    {
        $jsonData = Cache::remember('exported_translations', 600, function () {
            $translations = Translation::with('locale')
                ->select(['id', 'locale_id', 'key', 'content', 'tags'])
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'locale' => $t->locale->code,
                    'key' => $t->key,
                    'content' => $t->content,
                    'tags' => $t->tags,
                ]);

            $jsonString = json_encode($translations, JSON_PRETTY_PRINT);
            Storage::put('public/translations.json', $jsonString);
            return $jsonString;
        });
        return response()->json(json_decode($jsonData, true));
    }

}
