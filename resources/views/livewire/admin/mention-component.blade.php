<div style="display: inline-block;">
    <button wire:click="mention" 
            wire:loading.attr="disabled"
            class="btn btn-sm shadow-sm {{ $status == 2 ? 'btn-danger' : 'btn-light' }}"
            style="border-radius: 4px; min-width: 80px; transition: all 0.3s ease;">
        
        {{-- Default View --}}
        <span wire:loading.remove>
            <i class="fas {{ $status == 2 ? 'fa-bullhorn' : 'fa-plus-circle' }} mr-1"></i>
            {{ $status == 2 ? 'Mentioned' : 'Mention' }}
        </span>

        {{-- Loading View (Prevents double clicks) --}}
        <span wire:loading>
            <i class="fas fa-spinner fa-spin"></i>
        </span>
    </button>
</div>