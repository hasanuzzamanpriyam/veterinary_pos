<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ asset('storage/' . $setting->favicon) }}" type="image/x-icon" />
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    @endif

    <title>
        @isset($pageTitle)
            {{ $pageTitle }}
        @else
            @yield('page-title')
        @endif
         - {{ $setting->app_name ?? 'Inventory' }}
    </title>


    <!-- Bootstrap -->
    <link href="{{ asset('assets/cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!--alert plugins-->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

    <link href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">


    <!-- Data Table Style -->
    <link href="{{ asset('assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

        @stack('styles')
        <!-- Custom Theme Style -->
        <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">


        <link href="{{ asset('assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        @livewireStyles

</head>

<body class="nav-md">
    <div class="container body">
        <div class="row flex-nowrap">
            <div class="left_col col-md-2">
                <div class="scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('dashboard') }}" class="site_title d-flex align-items-center">
                            <span>{{ $setting->app_name ?? 'Inventory' }}</span>
                        </a>
                    </div>

                    <div class="clearfix"></div>


                    @include('admin.admin_partials.sidebar')

                </div>
            </div>

            <!-- Start main content/content body-->

            <div class="right_col col-md-10" role="main">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Header top navigation -->
                        @include('admin.admin_partials.header')
                        <!-- End Header top navigation -->
                    </div>
                </div>

                <div class="row">
                    @yield('main-content')
                </div>



                <!--Start footer content -->
                @include('admin.admin_partials.footer')
                <!-- End footer content -->
            </div>

            <!-- End main content/content body -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="{{ asset('assets/vendors/Chart.js/dist/Chart.min.js') }}"></script>

    <!--alert plugins-->
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('assets/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- bootstrap-datepicker -->
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <!---Data Table Scripts-->
    <script src="{{ asset('assets/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>



    {{-- select2 js cdn --}}
    <script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Custom Theme Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/build/js/custom.min.js') }}"></script>




    <script>
        // this is for laravel blade
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var link = $(this).attr('href');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error"
                    });
                }
            });
        });

        window.fzNumberFormater = function(number) {
            return number.toLocaleString('us', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        }

        // Sidebar Action Buttons (FullScreen, Lock, Logout)
        $(document).ready(function() {
            // FullScreen toggle
            $('#fullscreen-btn').on('click', function(e) {
                e.preventDefault();
                toggleFullScreen();
                animateButton($(this));
            });

            // Lock screen
            $('#lock-btn').on('click', function(e) {
                e.preventDefault();
                animateButton($(this));
                window.location.href = '{{ route('lock-screen') }}';
            });

            // Logout
            $('#logout-btn').on('click', function(e) {
                e.preventDefault();
                animateButton($(this));
                // Create a temporary logout form
                const logoutForm = $('<form>', {
                    'action': '{{ route('logout') }}',
                    'method': 'POST',
                    'style': 'display: none;'
                });
                logoutForm.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                $('body').append(logoutForm);
                logoutForm.submit();
            });

            // FullScreen toggle function
            function toggleFullScreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().then(function() {
                        changeFullScreenIcon(true);
                    }).catch(function(error) {
                        console.error('Error attempting to enable fullscreen:', error);
                    });
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        changeFullScreenIcon(false);
                    }
                }
            }

            // Change fullscreen icon
            function changeFullScreenIcon(isFullscreen) {
                const icon = $('#fullscreen-btn .glyphicon');
                if (isFullscreen) {
                    icon.removeClass('glyphicon-fullscreen');
                    icon.addClass('glyphicon-resize-small');
                    $('#fullscreen-btn').attr('title', 'Exit FullScreen');
                } else {
                    icon.removeClass('glyphicon-resize-small');
                    icon.addClass('glyphicon-fullscreen');
                    $('#fullscreen-btn').attr('title', 'FullScreen');
                }
            }

            // Listen for fullscreen changes (user presses ESC)
            document.addEventListener('fullscreenchange', function() {
                changeFullScreenIcon(!!document.fullscreenElement);
            });

            // Button animation on click
            function animateButton($btn) {
                $btn.addClass('button-clicked');
                setTimeout(function() {
                    $btn.removeClass('button-clicked');
                }, 300);
            }
        });

        //this is for livewire component
        window.addEventListener('show-delete-message', event => {
            $(document).ready(function() {

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirmed')
                        //Livewire.emit('deleteConfirmed');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelled",
                            text: "Your imaginary file is safe :)",
                            icon: "error"
                        });
                    }
                });

            });

            window.addEventListener('deleted', event => {
                swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
            });
        });

        /*dataTable sorting section start*/

        $(document).ready(function() {
            // Destroy the existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('table.category_list_table')) {
                $('table.category_list_table').DataTable().destroy();
            }

            // Re-initialize DataTable with your desired configuration
            $('table.category_list_table').DataTable({
                // Your DataTable configuration here
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ]
            });
        });
        $(document).on('click', '.modal-backdrop', function () {
            $('.modal').modal('hide'); // close any open modal
            $(this).remove(); // remove the clicked backdrop
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });

        /*dataTable sorting section end*/
    </script>
    {{-- alert notification --}}
    @if (Session::has('msg'))
        <script>
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('msg') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('msg') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('msg') }}");
                    break;
                case 'danger':
                    toastr.warning("{{ Session::get('msg') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('msg') }}");
                    break;
            }
        </script>
    @endif

    @livewireScripts

    @stack('scripts')
</body>

</html>
