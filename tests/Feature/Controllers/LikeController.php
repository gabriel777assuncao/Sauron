<?php

namespace Tests\Feature\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LikeController extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->question = Question::factory()->create();
    }

    public function test_if_it_will_be_able_like_a_question(): void
    {
        $this->actingAs($this->user);

        $request = $this->post(route('questions.like', $this->question), ['question' => $this->question->id]);
        $request->assertRedirect();

        $this->assertDatabaseHas('votes', [
            'likes' => 1,
            'question_id' => $this->question->id,
            'unlike' => 0,
            'user_id' => $this->user->id,
        ]);
    }
}
