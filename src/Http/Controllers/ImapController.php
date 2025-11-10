<?php

namespace Consulmedics\ImapAccess\Http\Controllers;

use App\Http\Controllers\Controller;
use Consulmedics\ImapAccess\Models\ImapMail;
use Illuminate\View\View;

class ImapController extends Controller
{

    public function show(ImapMail $message): View
    {
        $message->fetchDetails();
        $prevPage = 1;
        $prev = url()->previous() ? parse_url(url()->previous()) : [];

        if($prevQuery = $prev["query"] ?? null) {
            $params = [];
            parse_str($prevQuery, $params);
            $prevPage = $params["page"] ?? 1;
        }

        return view("imap-access::show", [
            "folder" => $message->type,
            "imapMail" => $message,
            "prevPage" => $prevPage
        ]);
    }


    public function setStatus(ImapMail $message, int $status = 1)
    {
        $message->update([
            "status" => $status
        ]);

        return redirect(route("admin.imap-mail.show", [$message]));
    }


    public function downloadAttachment(ImapMail $message, string $name)
    {
        $message->fetchDetails();

        $attach = $message->attachments
            ->filter(fn($attachement) => $attachement["name"] == $name)
            ->pluck("object")
            ->first();
        $pathToFile = storage_path("/app/public/" . optional($attach)->getFilename());
        file_put_contents($pathToFile, optional($attach)->getDecodedContent());

        return response()->download($pathToFile)->deleteFileAfterSend();
    }
}
