<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;

class CommentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user)
    {
        $mail = (new MailMessage)
                    ->subject($this->data->comment->user->name.'さんがあなたのエッセイにコメントしました')
                    ->line('こんにちは'.$this->data->user->name.'さん。')
                    ->line('あなたのエッセイ「'.$this->data->article->title.'」にコメントがとどきました。')
                    ->line('＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿')
                    ->line('【名前】'.$this->data->comment->user->name)
                    ->line('【内容】')
                    ->line($this->data->comment->body)
                    ->line('＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿')
                    ->action('エッセイ詳細へ', url($this->data->url))
                    ->line('Thank you for using our application!');
        //dd($this->data);
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
