<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\TranslationRepository;

class TranslationService
{
    protected $translationRepository;

    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function create(array $data): Translation
    {
        return $this->translationRepository->create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        return $this->translationRepository->update($translation, $data);
    }

    public function delete(Translation $translation): bool
    {
        return $this->translationRepository->delete($translation);
    }

    public function search($locale, $key)
    {
        return $this->translationRepository->search($locale, $key);
    }

    public function generateExportFile()
    {
        return $this->translationRepository->exportToJson();
    }

    public function getExportFilePath()
    {
        return storage_path('app/public/translations.json');
    }
}
