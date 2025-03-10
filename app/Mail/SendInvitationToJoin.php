<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInvitationToJoin extends Mailable
{
    public $invitation;
    public $user_exist;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(Invitation $invitation, bool $user_exist)
    {
        $this->invitation = $invitation;
        $this->user_exist = $user_exist;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Invitation To Join',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->user_exist) {
            $url = config('app.client_url').'/setting/teams' ;
            return (new Content(
                markdown: 'emails.invitation.invite-new-user',
            ))->with([
                'invitation' => $this->invitation,
                'url' => $url,
            ]);
        } else {
            $url = config('app.client_url').'/register?invitation=' . $this->invitation->recipient_email ;
            return (new Content(
                markdown: 'emails.invitation.invite-existing-user',
            ))->with([
                'invitation' => $this->invitation,
                'url' => $url,
            ]);
        }

    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
