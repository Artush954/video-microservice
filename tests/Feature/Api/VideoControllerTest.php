<?php

namespace Tests\Feature\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreVideoSuccessfully()
    {
        $link = 'https://www.youtube.com/watch?v=NJvpt7swmk8';

        $response = $this->postJson('/api/videos', ['link' => $link]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['message' => 'Video saved successfully']);

        $this->assertDatabaseHas('videos', ['link' => $link]);
    }

    public function testStoreVideoWithInvalidLink()
    {
        $link = 'https://example.com';

        $response = $this->postJson('/api/videos', ['link' => $link]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['link']);

        $this->assertDatabaseMissing('videos', ['link' => $link]);
    }
}
