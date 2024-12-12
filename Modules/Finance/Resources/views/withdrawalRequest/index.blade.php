@extends($layout)


@section('title', 'Redeem Request')

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
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="text" id="search-input" onkeyup="doneTyping()" data-kt-order-table-filter="search" class="form-control form-control-solid w-250px ps-15" autocomplete="off" placeholder="Search Withdrawal Request" value="{{$request->search??""}}" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                @can('extract_withdrawal_request')
                    <button id="exportButton"
                            class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                        <i class="ki-outline ki-exit-up fs-2"></i>
                        Export
                    </button>
                @endcan
            </div>
            <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
                <button type="button" class="btn btn-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                    <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                        fill="currentColor" />
                            </svg>
                        </span>
                    <!--end::Svg Icon-->Filter</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                     id="kt-toolbar-filter" data-popper-placement="bottom-end">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->
                    <form action="{{ route('withdrawalRequest.list', Request()->all()) }}" method="get">
                        <!--begin::Content-->
                        <div class="px-7 py-5">
                            <!--begin::Input group-->
                            <div class="mb-10">

                                <!--begin::Label-->
                                <label class="form-label fs-5 fw-semibold mb-3">Date:</label>
                                <!--end::Label-->

                                <!--begin::Options-->
                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                     data-kt-dropshipper-table-filter="joining_date">
                                    <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="created_at">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">From</label>
                                                    <input type="date" class="form-control" name="fromDate" value="{{ request('fromDate') ?? old('fromDate')}}" id="exampleFormControlInput1">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">To</label>
                                                    <input type="date" class="form-control" name="toDate" value="{{ request('toDate') ?? old('toDate')}}" id="exampleFormControlTextarea1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Options-->

                                <!--begin::Label-->
                                <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                <!--end::Label-->

                                <!--begin::Options-->
                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                     data-kt-dropshipper-table-filter="status">
                                    <!--begin::Option-->
                                    <label
                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input class="form-check-input" type="radio" name="status" value="pending"
                                                {{ request()->status == 'pending' ? 'checked' : '' }}>
                                        <span class="form-check-label text-gray-600">
                                                Pending
                                            </span>
                                    </label>
                                    <!--end::Option-->

                                    <!--begin::Option-->
                                    <label
                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input class="form-check-input" type="radio" name="status" value="approved"
                                                {{ request()->status == 'approved' ? 'checked' : '' }}>
                                        <span class="form-check-label text-gray-600">
                                                Approved
                                            </span>
                                    </label>
                                    <!--end::Option-->

                                    <!--begin::Option-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="radio" name="status" value="rejected"
                                                {{ request()->status == 'rejected' ? 'checked' : '' }}>
                                        <span class="form-check-label text-gray-600">
                                                Rejected
                                            </span>
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Options-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('withdrawalRequest.list') }}"
                                   id="reset_filter" class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                                   data-kt-user-table-filter="reset">Reset
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                    </form>
                    <!--end::Content-->
                </div>

                <div class="fw-bold me-5">


                </div>
                <!--end::Group actions-->
            </div>
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->

    <div class="card-body pt-0" id="data-table">
        @include('finance::withdrawalRequest.table')
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
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="closebutton();">Close</button>
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

    //user is "finished typing," do something
    function doneTyping() {
        let val = $("#search-input").val();
        var routeAll =
            "{{(request()->fullUrl() == request()->url()) ? request()->url().'?' : request()->fullUrl().'&'}}" +
            $("#filterDataForm").serialize();
        $('#data-table').html('<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>');

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

    function toggleActive(id, status, reason) {

        let toggleActiveRoute;
        if (status == 'approved') {

            toggleActiveRoute = "{{ url('withdrawalRequest/approved') }}/" + id;
            $.get({
                url: toggleActiveRoute,
                data: {
                    id: id,
                },
                success: function(data) {
                    // var remove = $('#row' + id);
                    // remove.html("");
                    var removebutton = $('#removebutton' + id);
                    console.log(removebutton);
                    removebutton.html("");
                    removebutton.text("No Result");
                    var stat = $('#status' + id);
                    document.getElementById('status' + id).style.color = "#4f20db";
                    stat.text(status);


                },
            });
        } else {
            $("#order_reuest").val(id);
            $("#reason").val(reason);
            $('#myModal').toggle();
        }


    }

    function saveReason() {
        let reason = $("#reason").val();
        let id = $("#order_reuest").val();
        toggleActiveRoute = "{{ url('withdrawalRequest/refused') }}/" + id;
        $.get({
            url: toggleActiveRoute,
            data: {
                id: id,
                reason: reason,
            },
            success: function(data) {

                $('#myModal').toggle();
                $('#myModal').hide();
                var removebutton = $('#removebutton' + id);
                removebutton.html("");
                removebutton.text("No Result");
                var stat = $('#status' + id);
                var reasonhtml = $('#reason' + id);
                reasonhtml.text(reason);
                document.getElementById('status' + id).style.color = "";

                stat.text("rejected");



            },
        });

    }
    $(document).ready(function() {
        $('#exportButton').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            inProgressStatus();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var search = $('[name="search"]').val();
            var fromDate = $('[name="fromDate"]').val();
            var toDate = $('[name="toDate"]').val();

            var formData = {
                'job': 'ExportRedeemRequestJob',
                'search': search,
                'fromDate': fromDate,
                'toDate': toDate,
                'status': @json(request('status')),
                'admin_id': @json(user()->id)
            };

            $.ajax({
                url: "{{ route('model.export') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                success: function(response) {
                    $('#exportButton').html(
                        '<i class="ki-outline ki-exit-up fs-2"></i> Export');

                    checkJobStatus();
                },
                error: function() {
                    Swal.fire({
                        text: "An error occurred while exporting.",
                        icon: "danger",
                        showCancelButton: false,
                        buttonsStyling: false,
                        confirmButtonText: "Will try later!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary",
                        },
                    }).then(function(result) {
                        // do nothing
                    });
                    $('#exportButton').html(
                        '<i class="ki-outline ki-exit-up fs-2"></i> Export');
                }
            });
        });

    });

</script>
@endpush
