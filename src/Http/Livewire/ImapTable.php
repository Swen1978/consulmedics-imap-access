<?php

namespace Consulmedics\ImapAccess\Http\Livewire;

use Consulmedics\ImapAccess\Models\ImapMail;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ImapTable extends LivewireDatatable
{
    public $perPage = 100;
    public $sort = "date|desc";
    public string $folder;


    public function builder()
    {
        return ImapMail::query()
            ->where("type", $this->folder);
    }


    public function columns()
    {
        return [
            Column::checkbox(),

            Column::callback(['status'], function($status) {
                return match($status) {
                    "0" => '<i title="'.__('Status unchecked').'" class="fa-solid fa-envelope" style="color: #e70000; font-size: 24px;"></i>',
                    "1" => '<i title="'.__('Status checked').'" class="fa-solid fa-file-check" style="color: #307d30; font-size: 24px;"></i>',
                };
            })
                ->alignCenter()
                ->label(__('Status'))
                ->filterable([
                    ["id" => 0, "name" => __('Status unchecked')],
                    ["id" => 1, "name" => __('Status checked')],
                ]),
            Column::name('from')
                ->label(__('From'))
                ->filterable(),
            Column::name('date')
                ->label(__('date'))
                ->defaultSort('desc')
                ->filterable(),
            Column::name('subject')
                ->label(__('Subject'))
                ->filterable(),
            Column::callback(["number"], function ($number) {
                $url = route("admin.imap-mail.show", [$number]);

                return "<a href=\"".$url."\">".__("Read")."</a>";
            })->label(__("Actions")),
        ];
    }


    public function updatedSelected()
    {
        $this->emit("selectionChanged", $this->selected);
    }
}
