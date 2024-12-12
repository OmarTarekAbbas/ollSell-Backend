@extends($layout)


@section('title', 'Wallet Recharge')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
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
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="text" id="search-input" data-kt-order-table-filter="search"
                    class="form-control form-control-solid w-250px ps-15" autocomplete="off"
                    placeholder="Recharge wallet requests" value="{{$request->search??""}}" disabled />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Filter-->
            <button type="button" class="btn btn-primary me-3 dropdown-toggle" id="dropdownMenuClickable"
                data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                <span class="svg-icon svg-icon-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->Filter
            </button>
            <!--begin::Menu 1-->
            <form method="GET"  action="{{route('depositRequest.list')}}"
                class="dropdown-menu w-300px w-md-325px" data-kt-menu="true" aria-labelledby="dropdownMenuClickable">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-5 text-dark fw-bold">Filter Options</div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Separator-->
                <!--begin::Content-->
                <div class="px-7 py-5" data-kt-user-table-filter="form">

                    <div class="mb-10">
                        <label class="form-label fs-6 fw-semibold">Status:</label>
                        <select name="status" id="status" class="form-select form-select-solid fw-bold"
                            data-kt-select2="true" data-placeholder="Select status" data-allow-clear="true"
                            data-kt-user-table-filter="role" data-hide-search="false">

                            <option value="">Selecte</option>
                            <option value="pending" @if("pending"==request('status')) selected @endif>pending</option>
                            <option value="approved" @if("approved"==request('status')) selected @endif>approved
                            </option>
                            <option value="rejected" @if("rejected"==request('status')) selected @endif>rejected
                            </option>
                        </select>
                    </div>
                    <div class="mb-10 d-flex justify-content-between">
                        <div class="mx-2">
                            <label class="form-label fs-6 fw-semibold">From:</label>

                            <input type="text" name="orderDateFrom" id="orderDateFrom" autocomplete="off" value="@if(request()->orderDateFrom) {{request()->orderDateFrom}} @endif"
                                style="margin-right:5px" class="form-select" />


                        </div>

                        <div>
                            <label class="form-label fs-6 fw-semibold">To:</label>

                            <input type="text" name="orderDateTo" id="orderDateTo" autocomplete="off" value="@if(request()->orderDateTo) {{request()->orderDateTo}} @endif"
                                style="margin-right:5px" class="form-select" />
                        </div>

                    </div>


                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{route('depositRequest.list')}}" id="reset_filter"
                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                            data-kt-user-table-filter="reset">Reset
                        </a>
                        <!-- <button type="reset" id="reset_filter" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-user-table-filter="reset">Reset</button> -->
                        <button type="submit" class="btn btn-primary fw-semibold px-6 filterDataForm"
                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply
                        </button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Content-->
            </form>


            <!--end::Filter-->



            <!--begin::Toolbar-->

            <!--end::Group actions-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->

    <div class="card-body pt-0" id="data-table">
        @include('finance::depositRequest.table')
    </div>

</div>
<!--end::Products-->

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Reason</h4>
                <button type="button" class="btn-close" onclick="closebutton();"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-2 col-form-label fw-semibold fs-6">Reason</label>
                        <div class="col-lg-10 d-flex align-items-center">
                            <textarea required name="reason" id="reason"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <input type="hidden" id="order_reuest" value="">
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                    onclick="closebutton();">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveReason()">Save</button>

            </div>

        </div>
    </div>
</div>
    </div>
</div>

@endsection

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection

@push('scripts')

<script>
let csrfToken = "{{ csrf_token() }}";
</script>
<script>
function closebutton() {
    $('#myModal').hide();
}

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
    doneTyping();
});

//on keydown, clear the countdown
// input.on('keydown', function () {
//     clearTimeout(typingTimer);
// });

//user is "finished typing," do something
function doneTyping() {
    let val = $("#search-input").val();
    var routeAll =
        "{{(request()->fullUrl() == request()->url()) ? request()->url().'?' : request()->fullUrl().'&'}}" +
        $("#filterDataForm").serialize();
    $('#data-table').html(
        '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
        );

    $.get({
        url: routeAll,
        data: {
            search: val,
        },
        success: function(data) {
            jQuery(document).ready(function() {
                $('#data-table').html(data);
                KTMenu.createInstances();

            });
        },
    });
}

// $(".filterDataForm").on("click", function(e) {

//     e.preventDefault();
//     let val = $("#search-input").val();
//     var routeAll =
//         "{{(request()->fullUrl() == request()->url()) ? request()->url().'?' : request()->fullUrl().'&'}}" +
//         $("#filterDataForm").serialize();
//     $('#data-table').html(
//         '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
//         );
//     $('#filterDataForm').removeClass('show');
//     console.log(routeAll);
//     $.get({
//         url: routeAll,
//         data: {
//             search: val,
//         },
//         success: function(data) {
//             jQuery(document).ready(function() {
//                 $('#data-table').html(data);
//                 KTMenu.createInstances();
//                 handleDeleteRows();
//             });
//         },
//     });
// });

$('#reset_filter').on('click', function() {
    $('#industry_id').val('').trigger('change');
    window.location = route;
});

$('#search-input').prop("disabled", false); // Element(s) are now enabled.
</script>
@endpush