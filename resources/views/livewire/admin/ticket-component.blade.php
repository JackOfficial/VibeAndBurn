<div class="mx-3">
     @if(Session::has('sendReplySuccess'))
              <div class="alert alert-success alert-sm">{{ Session::get('sendReplySuccess') }}</div>
              @endif
<div class="card direct-chat direct-chat-primary">
            <div class="card-header">
              <h3 class="card-title">Ticket ID #{{ $ticketID }}</h3>

              <div class="card-tools">
                <span title="3 New Messages" class="badge badge-primary">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                  <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">
                <!-- Message. Default to the left -->
                @foreach($ticket as $chat)
                  @if($chat->message != null)
                <div class="direct-chat-msg right">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-timestamp float-left">{{ $chat->created_at->format('d M h:i A') }}</span>
                  </div>
                  <!-- /.direct-chat-infos -->
                  @if($chat->google_id != null)
                  <img class="direct-chat-img" src="{{ $chat->avatar }}" alt="user profile picture">
                  @else
                  <img class="direct-chat-img" src="{{ asset('back/dist/img/user1-128x128.jpg') }}" alt="message user image">
                  @endif
                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                   {{ $chat->message }}
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                @endif
                <!-- /.direct-chat-msg -->
                
                <!-- Message to the right -->
                 @if($chat->reply != null)
                <div class="direct-chat-msg">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-timestamp float-right">{{ $chat->updated_at->format('d M h:i A') }}</span>
                  </div>
                  <!-- /.direct-chat-infos -->
                   <img class="direct-chat-img" src="{{ asset('back/dist/img/user1-128x128.jpg') }}" alt="message user image">
                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text text-right">
                      {{ $chat->reply }}
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                  @endif
                <!-- /.direct-chat-msg -->
                
                @endforeach

                

              </div>
              <!--/.direct-chat-messages-->

              <!-- Contacts are loaded here -->
              <div class="direct-chat-contacts">
                <ul class="contacts-list">
                 @forelse($chats as $chat)
                  <li>
                    <a href="/admin/ticket/{{ $chat->id }}">
                        
                         @if($chat->google_id != null)
                  <img class="contacts-list-img" src="{{ $chat->avatar }}" alt="user profile picture">
                  @else
                  <img class="contacts-list-img" src="{{ asset('back/dist/img/user1-128x128.jpg') }}" alt="user profile">
                  @endif

                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          {{ $chat->name }}
                          <small class="contacts-list-date float-right">{{ $chat->updated_at->format('m/d/Y') }}</small>
                        </span>
                        <span class="contacts-list-msg">How have you been? I was...</span>
                      </div>
                      <!-- /.contacts-list-info -->
                    </a>
                  </li>
                 @empty
                 <div>No chat!</div>
                 @endforelse
                </ul>
                <!-- /.contacts-list -->
              </div>
              <!-- /.direct-chat-pane -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <div>
                <div class="input-group">
                  <input type="text" wire:model="msg" placeholder="Type Message ..." class="form-control">
                  <span class="input-group-append">
                    <button type="button" class="btn btn-primary" wire:click="sendReply">Send<div wire:loading wire:target="sendReply">ing...</div></button>
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card-footer-->
          </div>
          </div>