<?php

namespace App\Notifications\Messages;

class SmsMessage
{
    public string $subject = '';
    public string $content = '';

    public function subject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
