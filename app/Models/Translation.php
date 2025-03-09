<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="Translation",
 *      title="Translation",
 *      description="Translation model schema",
 *      type="object",
 *      required={"locale_id", "key", "content"},
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="locale_id", type="integer", example=1),
 *      @OA\Property(property="key", type="string", example="welcome_message"),
 *      @OA\Property(property="content", type="string", example="Welcome to our platform"),
 *      @OA\Property(property="tags", type="string", example="web"),
 *      @OA\Property(property="created_at", type="string", format="date-time"),
 *      @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['locale_id', 'key', 'content', 'tags'];

    public function locale() {
        return $this->belongsTo(Locale::class, 'locale_id', 'id');
    }
}
