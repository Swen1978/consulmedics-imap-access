<?php

namespace Consulmedics\ImapAccess\Http\Controllers;

use App\Http\Controllers\Controller;
use Consulmedics\ImapAccess\Models\ImapMail;
use Illuminate\View\View;

class ImapController extends Controller
{
    public function index(string $folder): View
    {
        return view('imap::index', [
            'folder' => $folder,
            'label' => __(config("imap-access.folders.$folder.label"))
        ]);
    }


    public function show(ImapMail $message): View
    {
        $message->fetchDetails();

        return view("imap::show", [
            "folder" => $message->type,
            "imapMail" => $message
        ]);
    }


    public function setStatus(ImapMail $message, bool $status = true)
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
