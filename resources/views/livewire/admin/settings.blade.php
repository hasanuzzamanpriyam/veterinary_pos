<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Application Settings</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form wire:submit.prevent="save">
                    <div class="form-group">
                        <label for="app_name">App Name</label>
                        <input type="text" wire:model="app_name" class="form-control" id="app_name" required>
                        @error('app_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="website_name">Website Name</label>
                        <input type="text" wire:model="website_name" class="form-control" id="website_name" required>
                        @error('website_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="favicon">Favicon</label>
                        <input type="file" wire:model="favicon" class="form-control" id="favicon" accept="image/*">
                        @error('favicon') <span class="text-danger">{{ $message }}</span> @enderror
                        @if($favicon && !is_string($favicon))
                            <img src="{{ $favicon->temporaryUrl() }}" width="50" height="50" class="mt-2">
                        @elseif($favicon)
                            <img src="{{ asset('storage/' . $favicon) }}" width="50" height="50" class="mt-2">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
                @if (session()->has('message'))
                    <div class="alert alert-success mt-3">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
