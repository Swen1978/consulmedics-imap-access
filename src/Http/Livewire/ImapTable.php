<?php

namespace Consulmedics\ImapAccess\Http\Livewire;

use Consulmedics\ImapAccess\Models\ImapMail;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ImapTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public $perPage = 100;
    public $sort = "date|desc";
    public string $folder = "";
    public string $title = "";



    public function mount() {}


    public function table(Table $table): Table
    {
        return $table
            ->query(ImapMail::query()
                ->where("type", $this->folder))
            ->actions([
                Action::make("show")
                    ->url(fn($record) => route("admin.imap-mail.show", [$record->number]))
            ])
            ->bulkActions([
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
            ])
            ->columns([
                TextColumn::make('status')
                    ->formatStateUsing(function ($state) {
                        $result = match ($state) {
                            0 => '<i title="' . __('imap-access::imap-mail.status.not marked') . '" class="fa-solid fa-envelope" style="color: #e6ba2cff; font-size: 24px;"></i>',
                            1 => '<i title="' . __('imap-access::imap-mail.status.marked') . '" class="fa-solid fa-file-check" style="color: #307d30; font-size: 24px;"></i>',
                            2 => '<i title="' . __('imap-access::imap-mail.status.attachment faulty') . '" class="fa-solid fa-paperclip" style="color: #ff0000ff; font-size: 24px;"></i>',
                            default => $state
                        };
                        return new HtmlString($result);
                    })
                    ->alignCenter()
                    ->label(__('imap-access::imap-mail.Status')),
                TextColumn::make('from')
                    ->sortable()
                    ->label(__('From'))
                    ->searchable(),
                TextColumn::make('date')
                    ->sortable()
                    ->label(__("date")),
                TextColumn::make('subject')
                    ->label(__('imap-access::imap-mail.Subject'))
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        0 => "Nicht bearbeitet",
                        1 => "Erledigt",
                        2 => "Anhang fehlerhaft"
                    ])
            ])
            ->paginationPageOptions([10, 50, 100])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->searchPlaceholder(__("imap-access::imap-mail.searchPlaceholder"));
    }



    public function render()
    {
        return view('imap-access::imap-table');
    }
}
