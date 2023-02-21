<?php

namespace Consulmedics\ImapAccess\Http\Livewire;

use Consulmedics\ImapAccess\Models\ImapMail;
use Livewire\Component;

class ImapTableActionBar extends Component
{

    public bool $show = false;
    public array $selectedIds = [];
    protected $listeners = ["selectionChanged"];


    public function render()
    {
        return view('imap-access::table-action-bar');
    }


    public function selectionChanged($selected)
    {
        $this->show = !!count($selected);
        $this->selectedIds = $selected;
    }


    public function markAsReaded()
    {
        $this->mark(true);
    }


    public function markAsUnReaded()
    {
        $this->mark(false);
    }


    private function mark(bool $status)
    {
        ImapMail::query()->whereIn("id", $this->selectedIds)->update([
            "status" => $status
        ]);
        $this->emit('refreshLivewireDatatable');
    }
}
