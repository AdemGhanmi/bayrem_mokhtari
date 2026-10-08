<?php

namespace Tests\Feature;

use App\Models\MediaItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_every_public_page_renders_in_every_language(): void
    {
        foreach (['/', '/story', '/career', '/journal', '/gallery', '/contact'] as $url) {
            foreach (['en', 'fr', 'ar'] as $lang) {
                $this->get("$url?lang=$lang")->assertOk()->assertSee('lang="'.$lang.'"', false);
            }
        }
    }

    public function test_arabic_is_rtl_and_french_is_translated(): void
    {
        $this->get('/story?lang=ar')->assertSee('dir="rtl"', false)->assertSee('القصة');
        $this->get('/career?lang=fr')->assertSee('Carrière')->assertDontSee('The Career');
    }

    public function test_language_choice_persists_in_the_session(): void
    {
        $this->get('/lang/fr')->assertRedirect();
        $this->get('/journal')->assertSee('lang="fr"', false);
    }

    public function test_detail_pages_and_drafts(): void
    {
        $post = MediaItem::where('type', 'journal')->first();
        $this->get('/journal/'.$post->id)->assertOk();
        $post->update(['is_active' => false]);
        $this->get('/journal/'.$post->id)->assertNotFound();
    }

    public function test_contact_form_validates_and_stores(): void
    {
        $this->post('/contact', ['name' => '', 'email' => 'x', 'message' => 'short'])->assertSessionHasErrors(['name', 'email', 'message']);
        $this->post('/contact', ['name' => 'Ali', 'email' => 'ali@example.com', 'message' => 'A real message here.'])->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['email' => 'ali@example.com']);
    }

    public function test_every_ui_string_has_french_and_arabic(): void
    {
        $this->artisan('translations:missing')->assertSuccessful();
    }
}
