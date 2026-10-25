<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Verifikasi Permintaan Reset Password - Lost & Found FT UNTIRTA')
            ->greeting('Yth. ' . $notifiable->name . ',')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun Lost & Found FT UNTIRTA Anda.')
            ->line('Klik tombol berikut untuk memverifikasi identitas melalui email dan membuat password baru.')
            ->action('Verifikasi dan Atur Password', $resetUrl)
            ->line('Tautan ini berlaku selama 60 menit dan hanya dapat digunakan satu kali.')
            ->line('Jika Anda tidak meminta pengaturan ulang password, abaikan email ini. Password Anda tidak akan berubah.')
            ->salutation('Hormat kami, Tim Lost & Found FT UNTIRTA');
    }
}