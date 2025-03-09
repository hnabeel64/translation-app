<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $locale;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->locale = Locale::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test create translation */
    public function it_can_create_a_translation()
    {
        $response = $this->postJson('/api/translations', [
            'locale_id' => $this->locale->id,
            'key' => 'welcome_message',
            'content' => 'Welcome to our platform',
            'tags' => 'web'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('translations', [
            'key' => 'welcome_message',
            'content' => 'Welcome to our platform'
        ]);
    }

    /** @test update translation */
    public function it_can_update_a_translation()
    {
        $translation = Translation::factory()->create(['locale_id' => $this->locale->id]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'content' => 'Updated Content',
            'tags' => 'mobile'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'content' => 'Updated Content'
        ]);
    }

    /** @test delete a translation */
    public function it_can_delete_a_translation()
    {
        $translation = Translation::factory()->create(['locale_id' => $this->locale->id]);

        $response = $this->deleteJson("/api/translations/{$translation->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('translations', ['id' => $translation->id]);
    }

    /** @test validate translation creation */
    public function it_validates_translation_creation()
    {
        $response = $this->postJson('/api/translations', [
            'locale_id' => $this->locale->id,
            'key' => '',
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['key', 'content']);
    }

    /** @test validate update translation */
    public function it_validates_translation_update()
    {
        $translation = Translation::factory()->create(['locale_id' => $this->locale->id]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test export translation to json */
    public function it_can_export_translations_to_json()
    {
        Translation::factory(100)->create(['locale_id' => $this->locale->id]);

        $response = $this->get('/api/translations/export');

        $response->assertStatus(200);
        $this->assertTrue(Storage::exists('public/translations.json'));
    }
}
