<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\WithPagination;

class ContactMessages extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $subjectFilter = '';
    public $selectedMessage = null;
    public $showModal = false;
    public $replyText = '';
    public $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'subjectFilter' => ['except' => ''],
        'perPage' => ['except' => 25],
    ];

    public function mount()
    {
        $this->authorize('message-view');
    }

    public function render()
    {
        View::share('pageTitle', 'Contact Messages');

        $messages = ContactMessage::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('message', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->subjectFilter, function ($query) {
                $query->where('subject', $this->subjectFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate((int) $this->perPage);

        return view('livewire.admin.contact-messages', [
            'messages' => $messages,
            'newCount' => ContactMessage::where('status', 'new')->count(),
        ]);
    }

    public function viewMessage($id)
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);
        $this->replyText = $this->selectedMessage->admin_reply ?? '';
        $this->showModal = true;

        if ($this->selectedMessage->status === 'new') {
            $this->selectedMessage->update(['status' => 'read']);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedMessage = null;
        $this->replyText = '';
    }

    public function markAsRead($id)
    {
        $this->authorize('message-view');
        ContactMessage::findOrFail($id)->update(['status' => 'read']);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Message marked as read']);
    }

    public function markAsUnread($id)
    {
        $this->authorize('message-view');
        ContactMessage::findOrFail($id)->update(['status' => 'new']);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Message marked as unread']);
    }

    public function delete($id)
    {
        $this->authorize('message-delete');
        ContactMessage::findOrFail($id)->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Message deleted successfully']);
    }

    public function sendReply()
    {
        $this->authorize('message-reply');

        $this->validate([
            'replyText' => ['required', 'string'],
        ]);

        if ($this->selectedMessage) {
            $this->selectedMessage->update([
                'admin_reply' => $this->replyText,
                'status' => 'replied',
                'replied_at' => now(),
            ]);

            session()->flash('msg', 'Reply sent successfully!');
            session()->flash('alert-type', 'success');

            $this->closeModal();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }

    public function getSubjectLabel($subject)
    {
        return match($subject) {
            'product' => 'Product Inquiry',
            'order' => 'Order Information',
            'support' => 'Technical Support',
            'feedback' => 'Feedback',
            'other' => 'Other',
            default => ucfirst($subject),
        };
    }

    public function getStatusBadge($status)
    {
        return match($status) {
            'new' => '<span class="badge bg-danger">New</span>',
            'read' => '<span class="badge bg-info">Read</span>',
            'replied' => '<span class="badge bg-success">Replied</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($status) . '</span>',
        };
    }
}
