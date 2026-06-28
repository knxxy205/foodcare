<?php

namespace Tests\Feature;

use App\Models\ProgramDonasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_available(): void
    {
        $program = ProgramDonasi::factory()->create(['status' => 'aktif']);

        $this->get('/')->assertOk();
        $this->get('/programs')->assertOk()->assertSee($program->nama_program);
        $this->get('/programs/' . $program->id)->assertOk()->assertSee($program->nama_program);
        $this->get('/about')->assertOk();
        $this->get('/faq')->assertOk();
        $this->get('/contact')->assertOk();
    }
}

