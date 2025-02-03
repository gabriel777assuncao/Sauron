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
            'created_by' => $this->user->id,
        ]);

        $this->assertDatabaseCount('questions', 1);
        $this->assertDatabaseHas('questions', [
            'question' => Str::repeat('*', 254).'?',
            'created_by' => $this->user->id,
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
        $questions = Question::factory()->count(10)->for($this->user, 'createdBy')->create();

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

    public function test_if_it_will_edit_a_question(): void
    {
        $this->actingAs($this->user);
        $question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false]);

        $this->putJson(route('questions.publish', $question))
            ->assertRedirect();

        $this->assertNotTrue($question->draft);
        $this->assertDatabaseHas('questions', [
            'draft' => false,
        ]);
    }

    public function test_if_only_the_person_that_created_the_question_can_edit_it(): void
    {
        $viewUser = User::factory()->create();
        $this->actingAs($viewUser);

        $question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false]);

        $this->putJson(route('questions.publish', $question))
            ->assertForbidden();
    }

    public function test_if_only_auth_users_can_store_questions(): void
    {
        $questionData = [
            'question' => Str::repeat('*', 10).'?',
            'created_by' => $this->user->id,
        ];

        $this->post(route('questions.store'), $questionData)
            ->assertRedirect(route('login'));
    }

    public function test_if_the_user_can_see_the_questions_that_he_owns(): void
    {
        $this->actingAs($this->user);
        $otherUser = User::factory()->create();

        $otherQuestion = Question::factory()->for($otherUser, 'createdBy')->create(['draft' => false]);
        $question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false]);

        $this->get(route('questions.index'))->assertSee($question->question);
        $this->get(route('questions.index'))->assertDontSee($otherQuestion->question);
    }

    public function test_if_it_user_can_delete_his_own_question(): void
    {
        $this->actingAs($this->user);
        $question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false]);

        $this->delete(route('questions.destroy', $question))
            ->assertRedirect();

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);
    }

    public function test_if_it_will_be_able_to_open_a_question_to_edit(): void
    {
        $this->actingAs($this->user);

        $question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => true]);

        $this->get(route('questions.edit', $question))
            ->assertSuccessful()
            ->assertViewIs('questions.edit');
    }

    public function test_if_it_the_only_draft_questions_can_be_drafted(): void
    {
        $this->actingAs($this->user);

        $question1 = Question::factory()->for($this->user, 'createdBy')->create(['draft' => true]);
        $question2 = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false]);

        $this->get(route('questions.edit', $question1))
            ->assertValid()
            ->assertStatus(200);

        $this->get(route('questions.edit', $question2))
            ->assertValid()
            ->assertStatus(403);
    }
}
