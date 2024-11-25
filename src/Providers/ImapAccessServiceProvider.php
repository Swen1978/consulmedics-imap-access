<?php

namespace Consulmedics\ImapAccess\Providers;

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Consulmedics\ImapAccess\Http\Livewire\ImapTable;
use Consulmedics\ImapAccess\Http\Controllers\ImapController;
use Consulmedics\ImapAccess\Http\Livewire\ImapTableActionBar;
use Consulmedics\ImapAccess\Console\Commands\ImapMailsFetchCommand;
use Consulmedics\ImapAccess\Console\Commands\ImapMailsMarkOldAsFinished;

class ImapAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'imap-access');

        // Livewire Component
        Livewire::component('imap-access:imap-table', ImapTable::class);
        Livewire::component('imap-access:imap-table-action-bar', ImapTableActionBar::class);

        // Routes
        Route::middleware(['web', 'locale', 'auth_admin:admin'])
            ->prefix('admin/imap-mail/')
            ->as('admin.imap-mail.')
            ->group(function(){
                Route::get('list/{folder}', \Consulmedics\ImapAccess\Http\Livewire\ImapTable::class)->name('list');
                Route::get('show/{message:number}', [ImapController::class, 'show'])->name('show');
                Route::get('status-change/{message}/{status?}', [ImapController::class, 'setStatus'])->name('status-change');
                Route::get('download-attachment/{message:number}/{name}', [ImapController::class, 'downloadAttachment'])->name('download-attachment');
            });

        // Cronjob
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(ImapMailsFetchCommand::class)->hourly();
            $schedule->command(ImapMailsMarkOldAsFinished::class)->daily();
        });
    }

    public function register()
    {
        parent::register();

        $this->commands([
            ImapMailsFetchCommand::class,
            ImapMailsMarkOldAsFinished::class
        ]);
    }
}
