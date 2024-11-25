<div>
    <div class="grid grid-cols-2">
        <h3 class="mt-0">
            {{ __(config("imap-access.folders.$this->folder.label")) }}
        </h3>
    </div>
    <div class="card-body">
        {{ $this->table }}
    </div>
</div>