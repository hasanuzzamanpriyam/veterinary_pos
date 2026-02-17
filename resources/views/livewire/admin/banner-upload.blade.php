<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f5f7fa; padding: 20px;">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a6fd1;
            --success: #28a745;
            --danger: #dc3545;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .banner-container {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: var(--transition);
        }

        .banner-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 20px;
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .banner-header h2 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .banner-body {
            padding: 30px;
            background: white;
        }

        .upload-card {
            border: 2px dashed var(--primary);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            background: var(--light);
            transition: var(--transition);
        }

        .upload-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-3px);
        }

        .custom-file-label {
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            background: white;
            transition: var(--transition);
        }

        .custom-file-label:hover {
            border-color: var(--primary);
        }

        .custom-file-label::after {
            content: "Browse";
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            margin-left: 10px;
            transition: var(--transition);
        }

        .preview-card, .current-banner-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .preview-card:hover, .current-banner-card:hover {
            transform: translateY(-3px);
        }

        .card-header-custom {
            padding: 15px;
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }

        .banner-preview {
            border: 3px solid var(--success);
            border-radius: 8px;
            overflow: hidden;
        }

        .banner-preview img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            transition: var(--transition);
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.5);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, var(--danger) 0%, #c82333 100%);
            border: none;
            color: white;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220, 53, 69, 0.5);
        }

        .help-text {
            background: var(--light);
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .help-text h6 {
            color: var(--primary);
            font-weight: 600;
        }

        .alert-custom {
            border-left: 4px solid var(--primary);
            background: var(--light);
            padding: 15px;
            border-radius: 6px;
        }
    </style>

    <div class="banner-container">
        <div class="banner-header">
            <h2>
                <i class="fa fa-picture-o"></i> Banner Settings
            </h2>
        </div>

        <div class="banner-body">
            @if (session()->has('message'))
            <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show alert-custom" role="alert">
                <i class="fa fa-{{ session('alert-type') == 'success' ? 'check-circle' : 'info-circle' }} mr-2"></i>
                <strong>{{ session('message') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="upload-card">
                <div class="form-group mb-0">
                    <label for="banner" class="font-weight-bold" style="color: var(--primary); font-size: 16px;">
                        <i class="fa fa-cloud-upload mr-2"></i> Upload Banner Image
                    </label>
                    <p class="text-muted mb-3">
                        <i class="fa fa-info-circle mr-1"></i>
                        Recommended dimensions: <strong>1200x200px</strong> | Maximum file size: <strong>5MB</strong>
                    </p>
                    <div class="custom-file">
                        <input type="file" wire:model="banner" class="custom-file-input" id="bannerInput" accept="image/*">
                        <label class="custom-file-label" for="bannerInput">
                            <i class="fa fa-file-image-o mr-2"></i> Choose file...
                        </label>
                    </div>
                    @error('banner')
                    <small class="text-danger d-block mt-2">
                        <i class="fa fa-exclamation-triangle mr-1"></i> {{ $message }}
                    </small>
                    @enderror
                </div>
            </div>

            @if ($banner)
            <div class="preview-card mt-4">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="fa fa-eye mr-2"></i> Preview
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="banner-preview">
                        <img src="{{ $banner->temporaryUrl() }}" alt="Banner Preview">
                    </div>
                </div>
            </div>
            @endif

            @if ($currentBanner)
            <div class="current-banner-card mt-4">
                <div class="card-header-custom bg-info">
                    <h5 class="mb-0">
                        <i class="fa fa-picture-o mr-2"></i> Current Active Banner
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="banner-preview" style="border-color: var(--info);">
                        <img src="{{ $currentBanner }}" alt="Current Banner">
                    </div>
                </div>
            </div>
            @endif

            <div class="action-buttons">
                <div>
                    <button type="button"
                        wire:click="saveBanner"
                        class="btn btn-custom btn-primary-custom"
                        wire:loading.attr="disabled"
                        {{ !$banner ? 'disabled' : '' }}>
                        <i class="fa fa-save mr-2"></i>
                        <span wire:loading.remove wire:target="saveBanner">Save Banner</span>
                        <span wire:loading wire:target="saveBanner">
                            <i class="fa fa-spinner fa-spin mr-2"></i> Uploading...
                        </span>
                    </button>

                    @if ($currentBanner)
                    <button type="button"
                        wire:click="deleteBanner"
                        class="btn btn-custom btn-danger-custom ml-3"
                        wire:loading.attr="disabled"
                        onclick="return confirm('Are you sure you want to delete the banner? This action cannot be undone.')">
                        <i class="fa fa-trash mr-2"></i>
                        <span wire:loading.remove wire:target="deleteBanner">Delete Banner</span>
                        <span wire:loading wire:target="deleteBanner">
                            <i class="fa fa-spinner fa-spin mr-2"></i> Deleting...
                        </span>
                    </button>
                    @endif
                </div>

                <div class="text-muted">
                    <small>
                        <i class="fa fa-shield mr-1"></i> Super Admin Only
                    </small>
                </div>
            </div>

            <div class="help-text">
                <h6>
                    <i class="fa fa-question-circle mr-2"></i> Banner Guidelines
                </h6>
                <ul class="mb-0 pl-3">
                    <li>Use high-resolution images for best quality</li>
                    <li>Optimal dimensions: 1200x200 pixels (6:1 aspect ratio)</li>
                    <li>Supported formats: JPG, PNG, GIF</li>
                    <li>Banner will be displayed on all admin pages and welcome page</li>
                </ul>
            </div>
        </div>
    </div>
</div>
