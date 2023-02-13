<?php

namespace Consulmedics\ImapAccess\Models;

use Consulmedics\ImapAccess\Services\ImapMailboxService;
use Ddeboer\Imap\Message\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImapMail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'subject', 'from', 'date', "type", "status"
    ];


    public function fetchDetails(): static
    {
        // Get message details
        $config =  config("imap-access.folders." . $this->type);
        $message = ImapMailboxService::make($config)->getMessage($this->number);

        return $this->fillData($message, false);
    }


    public function fillData(\Ddeboer\Imap\Message $message, bool $forCreation = false): static
    {
        // Number
        $this->number = $message->getNumber();

        // Subject + From + Date
        $this->subject = $message->getSubject();
        $this->from = $message->getFrom()->getAddress();

        if($forCreation) {
            $this->date = $message->getDate();
            $this->status = 0;
        }
        else {
            // Date
            $this->date = $message->getDate()->format('d.m.Y h:m:s');

            // To
            $to = $message->getTo();
            $to_string = "";
            foreach ($to as $key => $t) {
                $to_string .= $t->getAddress();
                if ($key != array_key_last($to))
                    $to_string .= ", ";
            }
            $this->to = $to_string;

            // Text / Body
            $this->text = $message->getBodyHtml();
            if ($this->text == null) {
                $this->text = strip_tags($message->getBodyText());
                $this->html = false;
            }
            else {
                $this->html = true;
            }

            // Attachments
            $this->attachments = collect($message->getAttachments())
                ->filter(function(Attachment $attachment) {
                    return $attachment->getFilename();
                })
                ->map(function(Attachment $attachment) {
                    return [
                        "object" => $attachment,
                        "name" => $attachment->getFilename()
                    ];
                });
        }

        return $this;
    }
}
