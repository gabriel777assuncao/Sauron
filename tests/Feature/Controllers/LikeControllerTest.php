<?php

namespace Tests\Feature\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->question = Question::factory()->for($this->user, 'createdBy')->create(['draft' => false, 'question' => 'This is a very good question?']);
    }

    public function test_if_it_will_be_able_to_like_a_question(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.like', $this->question), ['question' => $this->question->id]);
        $request->assertRedirect();

        $this->assertDatabaseHas('votes', [
            'likes' => 1,
            'question_id' => $this->question->id,
            'unlikes' => 0,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_if_it_will_be_able_to_unlike_a_question(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.unlike', $this->question), ['question' => $this->question->id]);
        $request->assertRedirect();

        $this->assertDatabaseHas('votes', [
            'likes' => 0,
            'question_id' => $this->question->id,
            'unlikes' => 1,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_it_will_be_able_to_count_votes_correctly(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.unlike', $this->question), ['question' => $this->question->id]);
        $request->assertRedirect();

        $this->assertDatabaseHas('questions', [
            'votes_count' => 1,
        ]);
    }
}
