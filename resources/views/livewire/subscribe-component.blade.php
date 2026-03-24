<div class="w-100">
    <form class="subscribe-form-wrapper" wire:submit.prevent="subscribe">
        <div class="subscription-pill">
            <input 
                type="email" 
                wire:model="email" 
                class="subscribe-input @error('email') is-invalid @enderror" 
                placeholder="Enter email address..." 
                required 
            />
            
            <button type="submit" class="subscribe-action-btn" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="subscribe">
                    Subscribe <i class="fas fa-paper-plane ml-1"></i>
                </span>
                
                <span wire:loading wire:target="subscribe">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                </span>
            </button>
        </div>

        @error('email')
            <div class="error-message">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </div>
        @enderror
    </form>
</div>