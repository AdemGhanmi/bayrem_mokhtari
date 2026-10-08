<?php

namespace Tests\Feature;

use App\Models\CareerEntry;
use App\Models\MediaItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        $this->seed();
        $this->post('/admin/login', ['email' => 'admin@bayrem.tn', 'password' => 'ChangeMe123!'])->assertRedirect();
    }

    public function test_guests_are_sent_to_login(): void
    {
        foreach (['/admin', '/admin/pages', '/admin/settings', '/admin/messages', '/admin/content/career'] as $u) {
            $this->get($u)->assertRedirect('/admin/login');
        }
    }

    public function test_every_admin_screen_loads(): void
    {
        $this->login();
        foreach (['/admin', '/admin/pages', '/admin/pages/story', '/admin/settings', '/admin/messages'] as $u) {
            $this->get($u)->assertOk();
        }
        foreach (['career', 'honours', 'gallery', 'journal', 'video', 'diploma'] as $t) {
            $this->get("/admin/content/$t")->assertOk();
            $this->get("/admin/content/$t/create")->assertOk();
        }
    }

    public function test_career_crud(): void
    {
        $this->login();
        $this->post('/admin/content/career', ['club' => ['en' => 'Test FC', 'fr' => '', 'ar' => ''], 'period' => '2022', 'country' => 'Tunisia', 'is_active' => 1])->assertSessionHasNoErrors();
        $e = CareerEntry::where('period', '2022')->firstOrFail();
        $this->assertSame('Test FC', $e->text('club'));
        $this->assertSame('Test FC', $e->text('club', 'ar'), 'falls back to another language');
        $this->put("/admin/content/career/{$e->id}", ['club' => ['fr' => 'Équipe test'], 'period' => '2022', 'country' => 'Tunisia'])->assertSessionHasNoErrors();
        $this->assertSame('Équipe test', $e->fresh()->text('club', 'fr'));
        $this->assertFalse($e->fresh()->is_active, 'unchecked switch = draft');
        $this->delete("/admin/content/career/{$e->id}");
        $this->assertDatabaseMissing('career_entries', ['id' => $e->id]);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->login();
        $this->post('/admin/content/career', ['period' => '', 'country' => ''])->assertSessionHasErrors(['period', 'country']);
        $this->post('/admin/content/journal', ['title' => ['en' => 'x']])->assertSessionHasErrors('image');
    }

    public function test_image_upload_replace_and_delete_clean_files(): void
    {
        $this->login();
        $this->post('/admin/content/gallery', ['title' => ['en' => 'Pic'], 'image' => UploadedFile::fake()->image('a.jpg', 800, 600), 'is_active' => 1])->assertSessionHasNoErrors();
        $m = MediaItem::where('type', 'gallery')->latest('id')->first();
        $this->assertFileExists(public_path($m->image));
        $old = $m->image;
        $this->put("/admin/content/gallery/{$m->id}", ['title' => ['en' => 'Pic'], 'image' => UploadedFile::fake()->image('b.png', 800, 600)]);
        $this->assertFileDoesNotExist(public_path($old));
        $new = $m->fresh()->image;
        $this->assertFileExists(public_path($new));
        $this->delete("/admin/content/gallery/{$m->id}");
        $this->assertFileDoesNotExist(public_path($new));
    }

    public function test_non_images_are_rejected(): void
    {
        $this->login();
        $this->post('/admin/content/gallery', ['title' => ['en' => 'Bad'], 'image' => UploadedFile::fake()->create('x.php', 10, 'text/x-php')])->assertSessionHasErrors('image');
    }

    public function test_page_blocks_can_be_edited_and_hidden(): void
    {
        $this->login();
        $s = \App\Models\PageSection::where('page_slug', 'story')->where('section_key', 'hero')->firstOrFail();
        $this->put("/admin/pages/story/{$s->id}", ['title' => ['en' => 'Custom title', 'fr' => '', 'ar' => ''], 'is_active' => 1])->assertSessionHasNoErrors();
        $this->get('/story?lang=en')->assertSee('bm-story-hero', false)->assertSee('Custom');
        $this->put("/admin/pages/story/{$s->id}", ['title' => ['en' => 'Custom title']]); // is_active missing = hidden
        $this->get('/story?lang=en')->assertDontSee('bm-story-hero', false);
    }
}
