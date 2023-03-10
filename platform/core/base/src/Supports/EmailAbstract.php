<?php

namespace Botble\Base\Supports;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailAbstract extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $subject
     * @param array $data
     * @author Sang Nguyen
     */
    public function __construct($content, $subject, $data = [])
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @author Sang Nguyen
     */
    public function build()
    {
        $inlineCss = new CssToInlineStyles;
        $email = $this->from(setting('email_from_address', setting('admin_email', config('mail.from.address'))))
            ->subject($this->subject)
            ->html($inlineCss->convert($this->content));

        $attachments = Arr::get($this->data, 'attachments');
        if (!empty($attachments)) {
            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }
            foreach ($attachments as $file) {
                $email->attach($file);
            }
        }

        return $email;
    }
}
