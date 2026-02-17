<div>
    <div class="container-fluid">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel p-3">
                <div class="x_title">
                    <div class="header-title d-flex align-items-center gap-2">
                        <h2>Contact Messages</h2>
                        @if ($newCount > 0)
                            <span class="badge bg-danger">{{ $newCount }} New</span>
                        @endif
                    </div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {{ cute_loader() }}
                            <div class="table-header d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex gap-2 mr-auto">
                                    <div class="form-group m-0">
                                        <select id="perpage" class="form-control form-control-sm"
                                            wire:model.live="perPage">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                    <div class="form-group m-0">
                                        <select id="statusFilter" class="form-control form-control-sm"
                                            wire:model.live="statusFilter">
                                            <option value="">All Status</option>
                                            <option value="new">New</option>
                                            <option value="read">Read</option>
                                            <option value="replied">Replied</option>
                                        </select>
                                    </div>
                                    <div class="form-group m-0">
                                        <select id="subjectFilter" class="form-control form-control-sm"
                                            wire:model.live="subjectFilter">
                                            <option value="">All Subjects</option>
                                            <option value="product">Product Inquiry</option>
                                            <option value="order">Order Information</option>
                                            <option value="support">Technical Support</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ajax-search d-flex align-items-center gap-2">
                                    <div class="form-group m-0">
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Search by name, email, phone..." wire:model.live="search" />
                                    </div>
                                </div>
                            </div>

                            <div class="card-box table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">SL</th>
                                            <th class="all">Name</th>
                                            <th class="all">Email</th>
                                            <th class="all">Phone</th>
                                            <th class="all">Subject</th>
                                            <th class="all">Message</th>
                                            <th class="all">Status</th>
                                            <th class="all">Date</th>
                                            <th class="all">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($messages as $message)
                                            <tr>
                                                <td>{{ $messages->firstItem() + $loop->index }}</td>
                                                <td>{{ $message->name }}</td>
                                                <td>{{ $message->email }}</td>
                                                <td>{{ $message->phone }}</td>
                                                <td>{{ $this->getSubjectLabel($message->subject) }}</td>
                                                <td>
                                                    <div class="message-preview"
                                                        style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ Str::limit($message->message, 100) }}
                                                    </div>
                                                </td>
                                                <td>{!! $this->getStatusBadge($message->status) !!}</td>
                                                <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            wire:click="viewMessage({{ $message->id }})"
                                                            title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        @if ($message->status === 'new')
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                wire:click="markAsRead({{ $message->id }})"
                                                                title="Mark as Read">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        @endif
                                                        @if ($message->status !== 'new')
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                wire:click="markAsUnread({{ $message->id }})"
                                                                title="Mark as Unread">
                                                                <i class="fa fa-envelope"></i>
                                                            </button>
                                                        @endif
                                                        @can('message-delete')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                wire:click="delete({{ $message->id }})" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-5">
                                                    <p class="text-muted">No messages found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $messages->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($showModal)
            <div class="modal fade show" tabindex="-1" style="display: block;" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $this->getSubjectLabel($selectedMessage->subject) }}
                            </h5>
                            <button type="button" class="close" wire:click="closeModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($selectedMessage)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>From:</strong>
                                        <p>{{ $selectedMessage->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong>
                                        <p>{{ $selectedMessage->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Phone:</strong>
                                        <p>{{ $selectedMessage->phone }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Date:</strong>
                                        <p>{{ $selectedMessage->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Message:</strong>
                                        <div class="p-3 bg-light rounded mt-2" style="white-space: pre-wrap;">
                                            {{ $selectedMessage->message }}
                                        </div>
                                    </div>

                                    @if ($selectedMessage->admin_reply)
                                        <div class="col-md-12 mt-3">
                                            <strong>Previous Reply:</strong>
                                            <div class="p-3 bg-success-light rounded mt-2"
                                                style="white-space: pre-wrap; background-color: #d1e7dd;">
                                                {{ $selectedMessage->admin_reply }}
                                            </div>
                                            <p class="text-muted small">
                                                <em>Replied on:
                                                    {{ $selectedMessage->replied_at->format('M d, Y H:i') }}</em>
                                            </p>
                                        </div>
                                    @endif

                                    @can('message-reply')
                                        <div class="col-md-12 mt-3">
                                            <strong>Your Reply:</strong>
                                            <textarea class="form-control mt-2" rows="5" placeholder="Type your reply here..."
                                                wire:model.defer="replyText"></textarea>
                                            @error('replyText')
                                                <p class="text-danger small">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endcan
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            @can('message-reply')
                                <button type="button" class="btn btn-primary" wire:click="sendReply">
                                    <i class="fa fa-paper-plane"></i> Send Reply
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show" wire:ignore.self></div>
        @endif
    </div>

    <style>
        .message-preview {
            cursor: pointer;
            transition: color 0.2s;
        }

        .message-preview:hover {
            color: #2A3F54;
        }

        .badge {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
</div>
