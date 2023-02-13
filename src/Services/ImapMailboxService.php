<?php

namespace Consulmedics\ImapAccess\Services;

use Ddeboer\Imap\Connection;
use Ddeboer\Imap\MailboxInterface;
use Ddeboer\Imap\Search\Date\Since;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Server;

class ImapMailboxService
{
    protected Connection $connection;
    protected MailboxInterface $mailbox;


    public static function make(array $config): self
    {
        $obj = new static();
        $obj->connection = (new Server($config["server"]))
            ->authenticate($config["username"], $config["password"]);
        $obj->mailbox = $obj->connection->getMailbox($config["directory"]);

        return $obj;
    }


    public function getMessage(int $number)
    {
        return $this->mailbox->getMessage($number);
    }


    public function getMessages(?SearchExpression $searchExpression = null)
    {
        return $this->mailbox->getMessages($searchExpression);
    }


    public function getNewMessages($lastMessageDate)
    {
        $search = new SearchExpression();
        $search->addCondition(new Since($lastMessageDate));

        return $this->getMessages($search);
    }
}
