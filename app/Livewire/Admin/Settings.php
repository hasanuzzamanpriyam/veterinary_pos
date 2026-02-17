<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    public $app_name;
    public $website_name;
    public $favicon;

    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->app_name = $setting->app_name;
            $this->website_name = $setting->website_name;
            $this->favicon = $setting->favicon;
        }
    }

    public function save()
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'website_name' => 'required|string|max:255',
            'favicon' => 'nullable|image|max:1024', // 1MB max
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->app_name = $this->app_name;
        $setting->website_name = $this->website_name;

        if ($this->favicon) {
            $path = $this->favicon->store('favicons', 'public');
            $setting->favicon = $path;
        }

        $setting->save();

        session()->flash('message', 'Settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
