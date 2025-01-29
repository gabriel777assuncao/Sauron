<?php

namespace Tests\Feature\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_if_it_will_not_has_more_than_255_caracters(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.store'), [
            'question' => Str::repeat('*', 254).'?',
        ]);

        $request->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('questions', 1);
        $this->assertDatabaseHas('questions', [
            'question' => Str::repeat('*', 254).'?',
        ]);
    }

    public function test_if_it_will_has_a_question_mark_at_the_end(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.store'), [
            'question' => Str::repeat('*', 10),
        ]);

        $request->assertSessionHasErrors();
        $this->assertDatabaseCount('questions', 0);
        $this->assertDatabaseMissing('questions', [
            'question' => Str::repeat('*', 10),
        ]);
    }

    public function test_if_it_will_has_a_question_has_the_minimum_of_10_caracters(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.store'), [
            'question' => Str::repeat('*', 8).'?',
        ]);

        $request->assertSessionHasErrors();
        $this->assertDatabaseCount('questions', 0);
        $this->assertDatabaseMissing('questions', [
            'question' => Str::repeat('*', 8).'?',
        ]);
    }

    public function test_if_it_will_list_all_questions_correctly(): void
    {
        $this->actingAs($this->user);
        $questions = Question::factory()->count(10)->create();

        $request = $this->get(route('dashboard'));
        $request->assertStatus(200)
            ->assertViewIs('dashboard')
            ->assertViewHas('questions', function ($viewQuestions) use ($questions) {
                return $viewQuestions->count() === $questions->count();
            });

        $this->assertDatabaseCount('questions', 10);
    }

    public function test_if_it_will_create_a_question_always_in_draft(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.store'), [
            'question' => Str::repeat('*', 10).'?',
        ]);

        $this->assertDatabaseHas('questions', [
            'draft' => true,
        ]);
    }
}
