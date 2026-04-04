<section class="content">
    <div class="row">
        @include('admin.message-sidebar') 
        
        <div class="col-md-9">
            {{-- Feedback Alerts --}}
            @if (Session::has('feedback')) 
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm">
                    <button type="button" class="close" data-dismiss="alert">&times;</button> 
                    <i class="fas fa-info-circle mr-2"></i> {{ Session::get('feedback') }} 
                </div> 
            @endif

            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title">
                        Inbox 
                        @if(count($checkbox) > 0)
                            <span class="badge badge-primary ml-2">{{ count($checkbox) }} selected</span>
                        @endif
                    </h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            {{-- wire:model.debounce.500ms prevents server overload while typing --}}
                            <input type="text" class="form-control" wire:model.debounce.500ms="search" placeholder="Search by name or email...">
                            <div class="input-group-append">
                                <div class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="mailbox-controls border-bottom">
                        {{-- Select All Toggle --}}
                        <button type="button" wire:click="selectAllMessages" class="btn btn-default btn-sm">
                            <i class="{{ $selectAll ? 'fas fa-check-square text-primary' : 'far fa-square' }}"></i>
                        </button>

                        <div class="btn-group">
                            <button type="button" 
                                    wire:click="deleteMultipleMessages" 
                                    class="btn btn-default btn-sm" 
                                    onclick="confirm('Delete selected messages?') || event.stopImmediatePropagation()"
                                    @if(empty($checkbox)) disabled @endif>
                                <i class="far fa-trash-alt"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>
                        </div>
                        
                        <button type="button" wire:click="$refresh" class="btn btn-default btn-sm">
                            <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="$refresh"></i>
                        </button>
                        
                        <span class="small ml-2 text-muted" wire:loading wire:target="deleteMultipleMessages, delete">
                            Processing...
                        </span>

                        <div class="float-right">
                            {{-- Replaced static text with actual pagination summary --}}
                            <span class="text-muted small mr-2">
                                Showing {{ $messages->firstItem() }}-{{ $messages->lastItem() }} of {{ $messages->total() }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped mb-0">
                            <tbody>
                                @forelse($messages as $message)
                                    <tr wire:key="msg-{{ $message->id }}">
                                        <td>
                                            <div class="icheck-primary">
                                                <input type="checkbox" value="{{ $message->id }}" id="check{{ $message->id }}" wire:model="checkbox">
                                                <label for="check{{ $message->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="mailbox-star"><i class="fas fa-star text-warning"></i></td>
                                        <td class="mailbox-name">
                                            <a href="{{ route('admin.messages.show', $message->id) }}" class="text-dark font-weight-bold">
                                                {{ ucfirst($message->name) }}
                                            </a>
                                        </td>
                                        <td class="mailbox-subject text-muted">
                                            {{ Str::limit($message->message, 45) }}
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date text-sm">{{ $message->created_at->diffForHumans() }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-light btn-sm shadow-sm border" data-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item text-danger" href="#" wire:click.prevent="delete({{ $message->id }})" onclick="return confirm('Delete this message?')">
                                                        <i class="fas fa-trash-alt mr-2"></i> Delete
                                                    </a>
                                                    {{-- Fixed: Added quotes around the email string --}}
                                                    <a class="dropdown-item" href="#" wire:click.prevent="blockEmail('{{ $message->email }}')">
                                                        <i class="fas fa-ban mr-2"></i> Block Sender
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            No messages match your search.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Checkbox count footer --}}
                            @if(count($checkbox) > 0)
                                <small class="text-muted">{{ count($checkbox) }} items selected</small>
                            @endif
                        </div>
                        <div>
                            {{-- Actual Livewire Pagination Links --}}
                            {{ $messages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styling to make the mailbox look more like Gmail */
    .mailbox-messages table { table-layout: fixed; }
    .mailbox-name { width: 20%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mailbox-subject { width: 45%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mailbox-date { width: 15%; white-space: nowrap; }
    .pagination { margin-bottom: 0; }
</style>