@extends($layout)


@section('title', 'features')

@section('content')
<!--begin::Products-->
<div class="card card-flush">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="text" id="search-input" data-kt-order-table-filter="search" class="form-control form-control-solid w-250px ps-15" autocomplete="off" placeholder="Search Orders" value="{{$request->search??""}}" disabled />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">

            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-order-table-select="base">
                <!--begin::Add order-->
                <a href="{{ route('feature.create') }}" class="btn btn-primary"> Add feature</a>
                <!--end::Add order-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center" data-kt-order-table-select="selected">
                <div class="fw-bold me-5">
                    @permission('delete_orders')
                    {{-- <span class="me-2" data-kt-order-table-select="selected_count"></span>Selected</div> --}}
                    <button type="button" class="btn btn-danger ms-1 " data-kt-order-table-select="delete_selected">Delete Selected
                    </button>
                    @endpermission
                </div>
                <!--end::Group actions-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->

    </div>
    <div class="card-body pt-0" id="data-table">
        @include('subscription::feature.table')
    </div>
</div>
<!--end::Products-->
@endsection
@section('second-sidebar')
@include('subscription::layouts.sidebar')
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
            "{{(request()->fullUrl() == request()->url()) ? request()->url().'?' : request()->fullUrl().'&'}}" +
            $("#filterDataForm").serialize();
        $('#data-table').html('<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>');
        // update URL

        full = "{!! url()->full() !!}";
        if(full.substring(full.lastIndexOf('/') + 1) == 'logistics'){
            searchVal = "?search=" + val;
        }else{
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
                    window.history.pushState("data","Title",fullSearchLink);
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

    $(".filterDataForm").on("click", function(e) {

        e.preventDefault();
        let val = $("#search-input").val();
        var routeAll =
            "{{(request()->fullUrl() == request()->url()) ? request()->url().'?' : request()->fullUrl().'&'}}" +
            $("#filterDataForm").serialize();
        $('#data-table').html('<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>');
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
