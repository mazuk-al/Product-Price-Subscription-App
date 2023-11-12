<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserMail
 * @package App\Mail
 */
class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public array $content;

    /**
     * UserMail constructor.
     * @param array $content
     */
    public function __construct(array $content) {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->from('price_subscription@app.com', 'Price Subscription')
            ->view('emails.user_email', [
                'name' => $this->content['name'],
                'product_id' => $this->content['product_id'],
                'product_title' => $this->content['product_title'],
                'old_price' => $this->content['old_price'],
                'new_price' => $this->content['new_price']
            ]);
    }
}
