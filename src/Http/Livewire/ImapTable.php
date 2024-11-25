<?php

namespace Consulmedics\ImapAccess\Http\Livewire;

use Consulmedics\ImapAccess\Models\ImapMail;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ImapTable extends Component implements HasTable
{
    use InteractsWithTable;

    public $perPage = 100;
    public $sort = "date|desc";
    public string $folder = "";
    public string $title = "";


    protected function getTableQuery(): Builder|Relation
    {
        return ImapMail::query()
            ->where("type", $this->folder);
    }

    public function mount() {}

    protected function getTableActions(): array
    {
        return [
            Action::make("show")
                ->url(fn($record) => route("admin.imap-mail.show", [$record->number]))
        ];
    }


    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make("MarkAsRead")
                ->action(fn($records) => $records->each(function ($record) {
                    $record->status = 1;
                    $record->save();
                }))
                ->deselectRecordsAfterCompletion(),
            BulkAction::make("MarkAsUnread")
                ->action(fn($records) => $records->each(function ($record) {
                    $record->status = 0;
                    $record->save();
                }))
                ->deselectRecordsAfterCompletion(),
        ];
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('status')
                ->formatStateUsing(function ($state) {
                    $result = match ($state) {
                        0 => '<i title="' . __('Status unchecked') . '" class="fa-solid fa-envelope" style="color: #e70000; font-size: 24px;"></i>',
                        1 => '<i title="' . __('Status checked') . '" class="fa-solid fa-file-check" style="color: #307d30; font-size: 24px;"></i>',
                        default => $state
                    };
                    return new HtmlString($result);
                })
                ->alignCenter()
                ->label(__('Status')),
            TextColumn::make('from')
                ->sortable()
                ->label(__('From')),
            TextColumn::make('date')
                ->sortable()
                ->label(__("date")),
            TextColumn::make('subject')
                ->label(__('Subject'))
        ];
    }

    public function render()
    {
        return view('imap-access::imap-table');
    }
}
