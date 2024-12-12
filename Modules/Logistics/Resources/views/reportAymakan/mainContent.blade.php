<div class="row">
    <div class="col-xl-12" id="totalShipping">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5" onclick="selectHandler('total','Total Shipping')">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['allOrderInShipping']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Shipping Orders</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-6"id="internalShipping" onclick="selectHandler('internal','Internal Shipping')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['internal']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Internal Shipping Orders</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6" id="externalShipping" onclick="selectHandler('external','External Shipping')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5" >
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['external']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">External Shipping Orders</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4"  onclick="selectHandler('AY-0050','On-hold with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['onHold']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['onHold'] != 0)
                                {{ number_format(($orders['onHold'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders On-hold with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4" onclick="selectHandler('AY-0008','Return with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['return']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['return'] != 0)
                                {{ number_format(($orders['return'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders Return with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-4"onclick="selectHandler('AY-0005','Completed with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column" >
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['completed']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['completed'] != 0)
                                {{ number_format(($orders['completed'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders Completed with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-4" onclick="selectHandler('AY-0029','Canceled with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column" >
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['canceled']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['canceled'] != 0)
                                {{ number_format(($orders['canceled'] / $orders['allOrderInShipping']) * 100) }}%
                             @endif
                        </span>
                      
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders Canceled with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4" onclick="selectHandler('returnInprogress','Return In progress with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column"
                    >
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['returnInprogress']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['returnInprogress'] != 0)
                                {{ number_format(($orders['returnInprogress'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders Return In progress with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4" onclick="selectHandler('shipping','shipping with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column" >
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['shipping']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['shipping'] != 0)
                                {{ number_format(($orders['shipping'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders shipping with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    {{-- <div class="col-xl-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column" onclick="selectHandler('notAnyReply','not Any Reply with Aymakan')">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['notAnyReply']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['notAnyReply'] != 0)
                                {{ number_format(($orders['notAnyReply'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders not Any Reply with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div> --}}
</div>

<div class="modal fade" id="exampleModalOrders" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class=" modal-dialog modal-fullscreen p-9">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelOrders">Orders </h5>
                <button class="btn btn-success btn-sm " style=" margin-left: 999px;" onclick="exportOrders()">
                    <i class="fa fa-spinner fa-spin exportLoadingmoule" style="display: none"></i>
                    <i class="fa fa-file-export"></i>
                    Export
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="statusexport" value="" />
            <input type="hidden" id="type" value="" />
            <input type="hidden" id="fromDateexport" value="" />
            <input type="hidden" id="toDateexport" value="" />
            <input type="hidden" id="periodexport" value="" />
            <input type="hidden" id="dropshipperexport" value="" />
            <input type="hidden" id="paymentMethodExport" value="" />
            <input type="hidden" id="type_dateexport" value="" />
            <input type="hidden" id="created_platformexport" value="" />
            <input type="hidden" id="source_platformexport" value="" />
            

            <div class="modal-body" id="orders">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function exportOrders() {
        //  this.exportLoading = true;
        $(".exportLoadingmoule").show();
        var fromDate = '';
        var toDate = '';
        if ($("#fromDateexport").val()) {
            fromDate = $("#fromDateexport").val();
        }
        if ($("#toDateexport").val()) {
            toDate = $("#toDateexport").val();
        }
        var route = "{{ route('exportCustamOrdersReporting') }}";
        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                period: $("#periodexport").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                    "#periodexport").val(),

                fromDate: fromDate,
                toDate: toDate,
                type: $("#type").val(),
                status : $("#statusexport").val(),
                type_date : $("#type_dateexport").val(),
                created_platform : $("#created_platformexport").val(),
                source_platform : $("#source_platformexport").val(),
                paymentMethod : $("#paymentMethodExport").val(),
            },
            datatype: 'json',
            success: function(data) {
                const downloadUrl = data.url;
                // Create an anchor element
                const downloadLink = document.createElement("a");
                downloadLink.href = downloadUrl;
                downloadLink.target = "_blank"; // Open the link in a new tab
                downloadLink.setAttribute("download", ""); // Set the 'download' attribute to force download

                // Trigger a click event on the anchor element
                downloadLink.click();
                $(".exportLoadingmoule").hide();


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });


    };




    function selectHandler(type, colLabel) {

        var fromDate = '';
        var toDate = '';
        let status = $("#status").val();
        let type_date = $("#type_date").val();
        let created_platform = $("#created_platform").val();
        let source_platform = $("#source_platform").val();
        let paymentMethod = $("#paymentMethod").val();
        $('#exampleModalOrders').modal('toggle');
        $("#exampleModalLabelOrders").text("Orders " + colLabel);
        if ($("#fromDate").val()) {
            fromDate = $("#fromDate").val();
        }
        if ($("#toDate").val()) {
            toDate = $("#toDate").val();
        }

        $('#orders').html(
            '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
        );

        let period = $("#period").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
            "#period").val();
        var route = "{{ route('orderAymakan') }}";
        $.ajax({
            url: route,
            type: 'GET',
            data: {
                period: $("#period").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                    "#period").val(),
                fromDate: fromDate,
                toDate: toDate,
                type: type,
                status: status,
                dropshipper: $("#dropshipper").val(),
                type_date: type_date,
                created_platform : created_platform,
                source_platform: source_platform,
                paymentMethod : paymentMethod,

            },
            datatype: 'json',
            success: function(data) {
                $('#orders').html(data);
                $("#type").val(type);
                $("#statusexport").val(status);
                $("#fromDateexport").val(fromDate);
                $("#toDateexport").val(toDate);
                $("#periodexport").val(period);

                $("#type_dateexport").val(type_date);
                $("#created_platformexport").val(created_platform);
                $("#source_platformexport").val(source_platform);
                $("#paymentMethodExport").val(paymentMethod);
                $("#dropshipperexport").val($("#dropshipper").val());


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });

    }
</script>
