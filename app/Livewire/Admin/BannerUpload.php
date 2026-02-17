<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class BannerUpload extends Component
{
    use WithFileUploads;

    public $banner;
    public $currentBanner;

    public function mount()
    {
        $this->currentBanner = Auth::user()->banner_photo_url;
    }

    public function updatedBanner()
    {
        $this->validate([
            'banner' => 'image|max:5120', // 5MB Max
        ]);
    }

    public function saveBanner()
    {
        $this->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        $user = Auth::user();
        $user->updateBannerPhoto($this->banner);

        // Refresh the user model to get the updated banner path
        $user->refresh();

        $this->currentBanner = $user->banner_photo_url;
        $this->banner = null;

        session()->flash('message', 'Banner updated successfully!');
        session()->flash('alert-type', 'success');
    }

    public function deleteBanner()
    {
        Auth::user()->deleteBannerPhoto();

        $this->currentBanner = null;

        session()->flash('message', 'Banner deleted successfully!');
        session()->flash('alert-type', 'success');
    }

    public function render()
    {
        return view('livewire.admin.banner-upload');
    }
}
