<?php

namespace Tests\Unit\Command;

use App\Console\Commands\ClearOldOrUnpopularQuestions;
use App\Models\Question;
use App\Models\User;
use App\Notifications\NotifyUserAboutQuestionDeleted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ClearOldOrUnpopularQuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_old_or_unpopular_questions_and_notifies_users(): void
    {
        Notification::fake();
        Log::spy();

        $user = User::factory()->create();
        $oldQuestion = Question::factory()->create([
            'created_by' => $user->id,
            'created_at' => now()->subWeeks(2),
            'draft' => false,
        ]);

        $unpopularQuestion = Question::factory()->create([
            'created_by' => $user->id,
            'draft' => false,
            'votes_count' => 0,
        ]);

        $unpopularQuestion->votes()->create(['likes' => 0, 'unlikes' => 0, 'user_id' => $user->id]);

        $this->artisan(ClearOldOrUnpopularQuestions::class);

        $this->assertSoftDeleted('questions', ['id' => $oldQuestion->id]);
        $this->assertSoftDeleted('questions', ['id' => $unpopularQuestion->id]);

        Notification::assertSentTo($user, NotifyUserAboutQuestionDeleted::class);
    }
}
