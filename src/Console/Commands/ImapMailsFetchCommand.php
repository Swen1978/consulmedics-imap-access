<?php

namespace Consulmedics\ImapAccess\Console\Commands;

use Consulmedics\ImapAccess\Models\ImapMail;
use Consulmedics\ImapAccess\Services\ImapMailboxService;
use Illuminate\Console\Command;

class ImapMailsFetchCommand extends Command
{
    protected $signature = 'imap-mails:fetch {--since=6}'; // To be sure, get the last 6 hours, not only the ones from the last hour
    protected $description = 'Command description';


    public function handle()
    {
        foreach(config("imap-access.folders") AS $config) {
            $imap = ImapMailboxService::make($config);
            $date = now()->subHours($this->option("since"));
            $messages = $imap->getNewMessages($date);

            $this->info("Found " . $messages->count() . " messages for folder " . $config["directory"] .  " since ". $date);

            foreach($messages AS $msg) {
                /**
                 * @var \Ddeboer\Imap\Message $msg
                 * @var ImapMail $imapMail
                 */
                $imapMail = ImapMail::make();
                $imapMail->fillData($msg, true);

                if(!ImapMail::query()->where("number", $imapMail->number)->first()) {
                    $this->info("Added new mail with number " . $imapMail->number . ": \"" . $imapMail->subject . "\" from "  . $imapMail->date);
                    $imapMail->save();
                }
            }
        }
    }
}
