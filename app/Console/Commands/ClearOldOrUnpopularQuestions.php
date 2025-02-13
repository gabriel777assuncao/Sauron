<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use App\Notifications\NotifyUserAboutQuestionDeleted;
use Illuminate\Console\Command;
use Illuminate\Log\Logger;

class ClearOldOrUnpopularQuestions extends Command
{
    protected $signature = 'questions:clear';

    protected $description = 'Running command: clear likes cache';

    public function __construct(
        private readonly Question $question,
        private readonly Logger $logger,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Running command: Clear questions that are too old or have no votes');

        $this->question
            ->where('created_at', '<=', now()->subWeek())
            ->orWhere('votes_count', '=', 0)
            ->each(function ($question) {
                $this->logger->info("Deleting question: {$question->id}");

                $user = $question->createdBy()->first();

                if ($user instanceof User) {
                    $user->notify(new NotifyUserAboutQuestionDeleted($question->id));
                }

                $question->delete();
            });

        $this->info('Questions deleted and users notified.');
    }
}
