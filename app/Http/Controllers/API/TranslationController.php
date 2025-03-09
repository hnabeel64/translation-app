<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationStoreRequest;
use App\Http\Requests\TranslationUpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @OA\Info(
 *      title="Translation Management API",
 *      version="1.0.0",
 *      description="API for managing translations across multiple locales"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="BearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->middleware('auth:sanctum');
        $this->translationService = $translationService;
    }
    /**
     * @OA\Get(
     *      path="/api/translations/search/{locale}",
     *      operationId="searchTranslations",
     *      tags={"Translations"},
     *      summary="Search translations by locale and key",
     *      security={{"BearerAuth":{}}},
     *      @OA\Parameter(
     *          name="locale",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Translations found",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Translation")
     *          )
     *      )
     * )
     */
    public function search($locale, TranslationStoreRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->translationService->search($locale, $request->input('key', ''))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

     /**
     * @OA\Post(
     *      path="/api/translations",
     *      operationId="storeTranslation",
     *      tags={"Translations"},
     *      summary="Create a new translation",
     *      security={{"BearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"locale_id", "key", "content"},
     *              @OA\Property(property="locale_id", type="integer", example=1),
     *              @OA\Property(property="key", type="string", example="welcome_message"),
     *              @OA\Property(property="content", type="string", example="Welcome to our platform"),
     *              @OA\Property(property="tags", type="string", example="web")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Translation created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Translation")
     *      )
     * )
     */
    public function store(TranslationStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->translationService->create($request->validated()),
            201
        );
    }

    /**
     * @OA\Put(
     *      path="/api/translations/{id}",
     *      operationId="updateTranslation",
     *      tags={"Translations"},
     *      summary="Update an existing translation",
     *      security={{"BearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="content", type="string"),
     *              @OA\Property(property="tags", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Translation updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Translation")
     *      )
     * )
     */
    public function update(TranslationUpdateRequest $request, Translation $translation): JsonResponse
    {
        return response()->json(
            $this->translationService->update($translation, $request->validated()),
            200
        );
    }

    /**
     * @OA\Delete(
     *      path="/api/translations/{id}",
     *      operationId="deleteTranslation",
     *      tags={"Translations"},
     *      summary="Delete a translation",
     *      security={{"BearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Translation deleted successfully"
     *      )
     * )
     */
    public function destroy(Translation $translation): JsonResponse
    {
        $this->translationService->delete($translation);
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *      path="/api/translations/export",
     *      operationId="exportTranslations",
     *      tags={"Translations"},
     *      summary="Export translations as JSON",
     *      security={{"BearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Translations exported successfully",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Translation")
     *          )
     *      )
     * )
     */

     public function exportToJson(): BinaryFileResponse
     {
        $this->translationService->generateExportFile();
        $filePath = $this->translationService->getExportFilePath();
        if (!Storage::exists('public/translations.json')) {
            return response()->json(['error' => 'Error exporting translation'], 422);
        }
        return response()->download($filePath, 'translations.json', [
            'Content-Type' => 'application/json',
        ]);
     }
}
