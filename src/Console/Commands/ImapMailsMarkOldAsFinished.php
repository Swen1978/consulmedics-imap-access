<?php

namespace Consulmedics\ImapAccess\Console\Commands;


use Consulmedics\ImapAccess\Models\ImapMail;
use Illuminate\Console\Command;

class ImapMailsMarkOldAsFinished extends Command
{
    protected $signature = 'imap-mails:mark-old-as-finished {type=pool} {months=8}';
    protected $description = 'Marks all mails older then $months as finished';


    public function handle()
    {
        $months = $this->argument("months");
        $type = $this->argument("type");
        $query = ImapMail::query()
            ->where("type", $type)
            ->where("status", 0)
            ->whereDate("date", "<=", now()->subMonths($months));
        $count = $query->count();
        $query->update(["status" => 1]);
        $this->line("$count Einträge wurden auf Status 1 gesetzt (älter als $months Monate im Ordner $type)");
    }
}
