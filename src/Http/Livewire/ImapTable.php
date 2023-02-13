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
        return ImapMail::query();
    }


    public function columns()
    {
        return [
            Column::callback(['status'], function($status) {
                return match($status) {
                    "0" => '<i title="'.__('Status unchecked').'" class="mdi mdi-bookmark" style="color: #C49102; font-size: 24px;"></i>',
                    "1" => '<i title="'.__('Status checked').'" class="mdi mdi-bookmark-check" style="color: var(--primary); font-size: 24px;"></i>',
                };
            })
                ->alignCenter()
                ->label(__('Check Status'))
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
}
