@extends($layout)


@section('title', 'Refund Requests')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-2">
                </div>
                <div class="card-body pt-0" id="data-table">
                    @include('order::refund.table')
                </div>
            </div>
        </div>
    </div>
    <!--end::Products-->
@endsection
@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection

@push('scripts')
    <script>
        let csrfToken = "{{ csrf_token() }}";
    </script>
    <script>
        $(function() {


            $('input[name="orderDateFrom"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
            });
            $('input[name="orderDateTo"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
            });



            $('input[name="orderDateFrom"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                // defaultValue: null,

                locale: {

                    format: 'YYYY/MM/DD',
                }
            });

            $('input[name="orderDateFrom"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });



            $('input[name="orderDateTo"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY/MM/DD',
                }
            });

            $('input[name="orderDateTo"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


        });


        //setup before functions
        var typingTimer; //timer identifier
        var doneTypingInterval = 1000; //time in ms, 5 seconds for example
        var input = $('#search-input');

        //on keyup, start the countdown
        input.on('keyup', function() {
            let val = $("#search-input").val();
            var routeAll =
                "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->fullUrl() . '&' }}" +
                $("#filterDataForm").serialize();
            $('#data-table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );
            // update URL 

            full = "{!! url()->full() !!}";
            if (full.substring(full.lastIndexOf('/') + 1) == 'logistics') {
                searchVal = "?search=" + val;
            } else {
                searchVal = "&search=" + val;
            }
            var fullSearchLink = "{!! url()->full() !!}" + searchVal;
            console.log(full.substring(full.lastIndexOf('/') + 1));
            $.get({
                url: routeAll,
                data: {
                    search: val,
                },
                success: function(data) {
                    // jQuery(document).ready(function() {
                    window.history.pushState("data", "Title", fullSearchLink);
                    $('#data-table').html(data);
                    KTMenu.createInstances();

                    // });
                },
            });
        });

        //on keydown, clear the countdown
        // input.on('keydown', function () {
        //     clearTimeout(typingTimer);
        // });

        //user is "finished typing," do something
        function doneTyping() {

        }

        function toggleActive(id, status) {
            let toggleActiveRoute;
            if (status == 'approved') {
                toggleActiveRoute = "{{ url('order/approved/') }}/" + id;

            } else if (status == 'cancel_by_system') {
                toggleActiveRoute = "{{ url('order/cancel_by_system/') }}/" + id;


            } else if (status == 'deliveredAymakan') {
                toggleActiveRoute = "{{ url('order/deliveredAymakan/') }}";

            } else {
                toggleActiveRoute = "{{ url('order/refused/') }}/" + id;

            }

            $.get({
                url: toggleActiveRoute,
                data: {
                    id: id,
                },
                success: function(data) {
                    var remove = $('#remove' + id);
                    remove.html("");
                    var stat = $('#status' + id);
                    stat.text(status);
                    stat.style.color = "#a40ed6";

                },
            });
        }

        $(".filterDataForm").on("click", function(e) {

            e.preventDefault();
            let val = $("#search-input").val();
            var routeAll =
                "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->fullUrl() . '&' }}" +
                $("#filterDataForm").serialize();
            $('#data-table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );
            $('#filterDataForm').removeClass('show');
            console.log(routeAll);
            $.get({
                url: routeAll,
                data: {
                    search: val,
                },
                success: function(data) {
                    jQuery(document).ready(function() {
                        $('#data-table').html(data);
                        KTMenu.createInstances();
                        handleDeleteRows();
                    });
                },
            });
        });


        $('#reset_filter').on('click', function() {
            $('#industry_id').val('').trigger('change');
            window.location = route;
        });
        $('#search-input').prop("disabled", false); // Element(s) are now enabled.
    </script>
@endpush
