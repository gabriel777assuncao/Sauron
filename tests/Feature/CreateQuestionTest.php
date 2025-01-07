<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use function PHPUnit\Framework\assertTrue;

class CreateQuestionTest extends TestCase
{

    public function test_if_it_will_not_has_more_than_255_caracters(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->post(route('questions.store'), [
            'question' => Str::repeat('*', 254) . '?',
            ]);

        $request->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('questions', 1);
        $this->assertDatabaseHas('questions', [
            'question' => Str::repeat('*', 254) . '?',
        ]);
    }

    public function test_if_it_will_has_a_question_mark_at_the_end(): void
    {
        assertTrue(true);
    }
}
