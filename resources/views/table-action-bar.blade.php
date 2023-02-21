<div class="text-right">
    @if($show)
        <button class="ml-1 btn btn-secondary" wire:click="markAsReaded">
            {{ __('Mark readed') }}
        </button>
        <button class="ml-1 btn btn-secondary" wire:click="markAsUnReaded">
            {{ __('Mark unreaded') }}
        </button>
    @endif
</div>
