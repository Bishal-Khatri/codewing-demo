<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;

class PeopleTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_people_page(): void
    {
        $response = $this->get('/people');

        $response->assertStatus(302);
    }

    public function test_authenticated_user_can_access_people_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/people');

        $response->assertStatus(200);
    }

    public function test_file_upload(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('user.txt');

        $response = $this->actingAs($user)->post(route('people.upload'), ['file' => $file]);

        $response->assertStatus(200);
    }
}
