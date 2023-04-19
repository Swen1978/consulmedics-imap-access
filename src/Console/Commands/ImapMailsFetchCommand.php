<?php

namespace Consulmedics\ImapAccess\Console\Commands;

use Carbon\Carbon;
use Consulmedics\ImapAccess\Models\ImapMail;
use Consulmedics\ImapAccess\Services\ImapMailboxService;
use Illuminate\Console\Command;

class ImapMailsFetchCommand extends Command
{
    protected $signature = 'imap-mails:fetch';
    protected $description = 'Fetch imap mails from configured folders config(imap-access.folders)';


    public function handle()
    {
        foreach(config("imap-access.folders") AS $configName => $config) {
            // get last entry
            $last = ImapMail::query()
                ->where("type", $configName)
                ->orderBy("date", "DESC")
                ->first();
            $date = Carbon::create($last && $last->date ? $last->date : "1970-01-01");

            // Get messages
            $imap = ImapMailboxService::make($config);
            $messages = $imap->getNewMessages($date);

            // Log info
            $this->info("Found " . $messages->count() . " messages for folder " . $config["directory"] .  " since ". $date);

            foreach($messages AS $msg) {
                /**
                 * @var \Ddeboer\Imap\Message $msg
                 * @var ImapMail $imapMail
                 */
                $imapMail = ImapMail::make();
                $imapMail->fillData($msg, true);
                $imap->type = $configName;

                if(!ImapMail::query()->where("number", $imapMail->number)->first()) {
                    $this->info("Added new mail with number " . $imapMail->number . ": \"" . $imapMail->subject . "\" from "  . $imapMail->date->format("Y-m-d H:i:s"));
                    $imapMail->save();
                }
            }
        }
    }
}
