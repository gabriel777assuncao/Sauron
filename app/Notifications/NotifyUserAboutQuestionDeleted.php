<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserAboutQuestionDeleted extends Notification
{
    use Queueable;

    public function __construct(protected readonly int $questionId) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sua pergunta foi excluída')
            ->greeting("Olá, {$notifiable->name}")
            ->line("A pergunta #'{$this->questionId}' foi excluída do sistema.")
            ->line('Se tiver dúvidas, entre em contato com o suporte.')
            ->action('Ver Perguntas', url('/questions'))
            ->line('Obrigado por usar nossa plataforma!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Sua pergunta #'{$this->questionId}' foi excluída.",
            'deleted_at' => now(),
        ];
    }
}
