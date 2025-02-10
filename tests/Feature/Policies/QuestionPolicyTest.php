<?php

namespace Tests\Feature\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestionPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
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

    public function test_if_it_user_can_archive_his_own_question(): void
    {
        $rightUser = User::factory()->create();
        $wrongUser = User::factory()->create();
        $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

        $this->actingAs($wrongUser);

        $this->patch(route('questions.archive', $question))
            ->assertForbidden();

        $this->actingAs($rightUser);

        $this->patch(route('questions.archive', $question))
            ->assertRedirect();
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
}
