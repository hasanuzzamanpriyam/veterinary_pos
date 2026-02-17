@extends('layouts.admin')

@section('page-title', 'Edit Role')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Role: {{ $role->name }}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($role->name === 'Super Admin')
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        You are editing the Super Admin role. You can modify permissions, but critical permissions (role-edit, role-delete, user-role-assign) cannot be removed for security.
                    </div>
                @endif

                <!-- Debug Info -->
                <div class="alert alert-info">
                    <strong>Debug Info:</strong><br>
                    Total Modules: {{ count($permissions) }}<br>
                    Total Permissions: {{ $permissions->flatten()->count() }}<br>
                    Current Role Permissions: {{ count($rolePermissions) }}
                </div>

                <form action="{{ route('roles.update', $role->id) }}" method="POST" id="roleForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $role->name) }}" 
                               {{ $role->name === 'Super Admin' ? 'readonly' : '' }} 
                               required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label><strong>Assign Permissions</strong></label>
                        <p class="text-muted">Check permissions to assign them to this role.</p>
                        
                        <div class="row">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="background-color: #f5f5f5;">
                                            <label style="margin: 0; font-weight: normal;">
                                                <input type="checkbox" 
                                                       class="select-all-module" 
                                                       data-module="{{ $module }}"
                                                       {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                                                       style="margin-right: 8px;">
                                                <strong>{{ ucfirst($module) }} Module ({{ $modulePermissions->count() }} permissions)</strong>
                                            </label>
                                        </div>
                                        <div class="panel-body" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($modulePermissions as $permission)
                                                <div style="margin-bottom: 5px; padding: 3px;">
                                                    <label style="font-weight: normal; margin: 0; display: block;">
                                                        <input type="checkbox" 
                                                               class="permission-checkbox module-{{ $module }}" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->name }}" 
                                                               id="perm_{{ $permission->id }}"
                                                               {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                                               {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                                                               style="margin-right: 8px;">
                                                        <span>{{ $permission->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Selection Counter -->
                        <div class="alert alert-success" style="margin-top: 15px;">
                            <strong>Selected Permissions: <span id="selectedCount">0</span></strong>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        @if($role->name !== 'Super Admin')
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fa fa-save"></i> Update Role
                            </button>
                        @endif
                        <a href="{{ route('roles.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                        @if($role->name !== 'Super Admin')
                            <button type="button" class="btn btn-info" id="debugBtn">
                                <i class="fa fa-bug"></i> Debug Form
                            </button>
                        @endif
                    </div>
                </form>

                <!-- Debug Output -->
                <div id="debugOutput" style="display: none; margin-top: 20px;">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <strong>Debug Output</strong>
                        </div>
                        <div class="panel-body">
                            <pre id="debugContent" style="max-height: 300px; overflow-y: auto;"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Role edit form initialized');
        console.log('Current role: {{ $role->name }}');
        
        // Function to update selected count
        function updateSelectedCount() {
            var count = $('.permission-checkbox:checked').length;
            $('#selectedCount').text(count);
            console.log('Selected permissions: ' + count);
        }
        
        // Select/Deselect all permissions in a module
        $('.select-all-module').on('change', function() {
            if (!$(this).is(':disabled')) {
                var module = $(this).data('module');
                var isChecked = $(this).is(':checked');
                $('.module-' + module + ':not(:disabled)').prop('checked', isChecked);
                updateSelectedCount();
                console.log('Module ' + module + ' - ' + (isChecked ? 'selected all' : 'deselected all'));
            }
        });
        
        // Update count when individual checkboxes change
        $('.permission-checkbox').on('change', function() {
            if (!$(this).is(':disabled')) {
                updateSelectedCount();
                
                // Update module checkbox state
                var moduleClass = $(this).attr('class').match(/module-\S+/);
                if (moduleClass) {
                    var module = moduleClass[0].replace('module-', '');
                    var total = $('.module-' + module + ':not(:disabled)').length;
                    var checked = $('.module-' + module + ':checked:not(:disabled)').length;
                    $('.select-all-module[data-module="' + module + '"]').prop('checked', total === checked);
                }
            }
        });
        
        // Debug button
        $('#debugBtn').on('click', function(e) {
            e.preventDefault();
            var formData = $('#roleForm').serializeArray();
            var permissions = formData.filter(function(item) {
                return item.name === 'permissions[]';
            });
            
            var debugInfo = {
                'Total form fields': formData.length,
                'Role name': $('#name').val(),
                'Permissions selected': permissions.length,
                'Permission values': permissions.map(function(p) { return p.value; }),
                'All checkboxes': $('.permission-checkbox').length,
                'Checked checkboxes': $('.permission-checkbox:checked').length,
                'Disabled checkboxes': $('.permission-checkbox:disabled').length,
                'Form data': formData
            };
            
            $('#debugContent').text(JSON.stringify(debugInfo, null, 2));
            $('#debugOutput').show();
            console.log('Debug Info:', debugInfo);
        });
        
        // Form submission
        $('#roleForm').on('submit', function(e) {
            var selectedCount = $('.permission-checkbox:checked:not(:disabled)').length;
            console.log('Submitting form with ' + selectedCount + ' permissions');
            
            if (selectedCount === 0) {
                if (!confirm('You have not selected any permissions. Continue anyway?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        // Initial count and module checkbox states
        updateSelectedCount();
        
        // Set initial state of "select all" checkboxes based on current selections
        $('.select-all-module').each(function() {
            var module = $(this).data('module');
            var total = $('.module-' + module + ':not(:disabled)').length;
            var checked = $('.module-' + module + ':checked:not(:disabled)').length;
            if (total > 0 && total === checked) {
                $(this).prop('checked', true);
            }
        });
    });
</script>
@endpush
