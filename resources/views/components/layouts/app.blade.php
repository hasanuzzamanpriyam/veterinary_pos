<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Firoz Enterprise' }}</title>

    <link href="{{ asset('assets/cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}"
        rel="stylesheet">

    @stack('styles')
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body class="nav-md">
    <div class="container body">
        <div class="row flex-nowrap">
            <div class="left_col col-md-2">
                <div class="scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('dashboard') }}" class="site_title">
                            {{-- <img src="{{ asset('assets/images/brand_logo.png') }}" alt="Logo" width="50"
                                height="50" class="rounded-circle"> --}}

                            <span>Firoz Enterprise</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!--Sidebar menu profile quick info -->

                    <!-- End Sidebar menu profile quick info -->


                    @include('admin.admin_partials.sidebar')
                    <!--Start sidebar menu -->

                    <!-- End sidebar menu -->

                    <!-- Sidebar menu footer buttons -->

                    <!-- End Sidebar menu footer buttons -->
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
                    {{ $slot }}
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

    <script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Custom Theme Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/build/js/custom.min.js') }}"></script>




    <script>
        // this is for laravel blade
        $(document).on('click', '#delete', function (e) {
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


        //this is for livewire component
        window.addEventListener('show-delete-message', event => {
            $(document).ready(function () {

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

        $(document).ready(function () {
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