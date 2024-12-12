<div class="row">
    <div class="col-xl-6" id="totalShipping" onclick="selectHandler('total','Total Shipping')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
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
    <div class="col-xl-6"id="internalShipping">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['submitAwb']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Submitted AWBs </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4"id="receiveWarehouse">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['receiveWarehouse']) }}</span>

                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Receveied
                    </span>

                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Delivered
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['receiveWarehouseDeliver']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['receiveWarehouseDeliver'] != 0)
                                {{ number_format(($orders['receiveWarehouseDeliver'] / $orders['receiveWarehouse']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Returned
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['receiveWarehouseReturn']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['receiveWarehouseReturn'] != 0)
                                {{ number_format(($orders['receiveWarehouseReturn'] / $orders['receiveWarehouse']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4"id="internal">
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

                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Internal

                    </span>

                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Delivered
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['internalDeliver']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['internalDeliver'] != 0)
                                {{ number_format(($orders['internalDeliver'] / $orders['internal']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Returned
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['internalReturn']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['internalReturn'] != 0)
                                {{ number_format(($orders['internalReturn'] / $orders['internal']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4"id="external">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['external']) }}</span>

                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">External

                    </span>

                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Delivered
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['externalDeliver']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['externalDeliver'] != 0)
                                {{ number_format(($orders['externalDeliver'] / $orders['external']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Returned
                        <span
                            class="fs-1hx fw-bold text-dark me-1 lh-1 ls-n1">{{ number_format($orders['externalReturn']) }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['externalReturn'] != 0)
                                {{ number_format(($orders['externalReturn'] / $orders['external']) * 100) }}%
                            @endif
                        </span>

                    </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <br>
    <h1>Shipment Status</h1>
    <div class="col-xl-4" onclick="selectHandler('AY-0005','Delivered with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['delivered']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['delivered'] != 0)
                                {{ number_format(($orders['delivered'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders Delivered with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-4"onclick="selectHandler('AY-0008','returned with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['returned']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['returned'] != 0)
                                {{ number_format(($orders['returned'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders returned with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-4" onclick="selectHandler('AY-0001','AWB Created with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['aWBCreated']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['aWBCreated'] != 0)
                                {{ number_format(($orders['aWBCreated'] / $orders['allOrderInShipping']) * 100) }}%
                            @endif
                        </span>

                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders AWB Created </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4" onclick="selectHandler('AY-0009','In-Transits with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['inTransit']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['inTransit'] != 0)
                                {{ number_format(($orders['inTransit'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6"> Orders In-Transit with Aymakan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4" onclick="selectHandler('AY-0050','On-hold with Aymakan')">
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
                            @endif
                        </span>

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
    <div class="col-xl-4" onclick="selectHandler('AY-0026','Received At Riyadh Warehouse with Aymakan')">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['receivedAtRiyadhWarehouse']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['receivedAtRiyadhWarehouse'] != 0)
                                {{ number_format(($orders['receivedAtRiyadhWarehouse'] / $orders['allOrderInShipping']) * 100) }}%
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Received At Riyadh Warehouse</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <br>
    <h1> Average Lead Time</h1>
    <div class="col-xl-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['RTFD']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['RTFD'] != 0)
                                {{ number_format($orders['RTFD'] / $orders['allOrderInShipping']) }} Hours
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">First Delivery Attempt
                        From Receiving</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['FDTLD']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['FDTLD'] != 0)
                                {{ number_format($orders['FDTLD'] / $orders['allOrderInShipping']) }} Hours
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Last Delivery From First Attempt
                    </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span
                            class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($orders['OVERALL']) }}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if ($orders['OVERALL'] != 0)
                                {{ number_format($orders['OVERALL'] / $orders['allOrderInShipping']) }} Hours
                        </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Last Update Date From Receiving</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalOrders" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
            <input type="hidden" id="type" value="" />
            <input type="hidden" id="fromDateexport" value="" />
            <input type="hidden" id="toDateexport" value="" />
            <input type="hidden" id="periodexport" value="" />
            <input type="hidden" id="dropshipperexport" value="" />

            <input type="hidden" id="shippingTypeexport" value="" />
            <input type="hidden" id="shipmentStatusexport" value="" />


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
        var route = "{{ route('exportInsightsOrdersReporting') }}";
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
                dropshipper: $("#dropshipperexport").val(),
                shippingType: $("#shippingTypeexport").val(),
                shipmentStatus: $("#shipmentStatusexport").val(),
            },
            datatype: 'json',
            success: function(data) {

                setTimeout(function() {
                    const downloadUrl = data.url;
                    // Create an anchor element
                    const downloadLink = document.createElement("a");
                    downloadLink.href = downloadUrl;
                    downloadLink.target = "_blank"; // Open the link in a new tab
                    downloadLink.setAttribute("download",
                        ""); // Set the 'download' attribute to force download

                    // Trigger a click event on the anchor element
                    downloadLink.click();
                    $(".exportLoadingmoule").hide();
                }, 60000)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        });


    };

    function selectHandler(type, colLabel) {

        var fromDate = '';
        var toDate = '';
        let shippingType = $("#shippingType").val();
        let shipmentStatus = $("#shipmentStatus").val();

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
        var route = "{{ route('orderAymakanInsights') }}";
        $.ajax({
            url: route,
            type: 'GET',
            data: {
                period: $("#period").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                    "#period").val(),
                fromDate: fromDate,
                toDate: toDate,
                type: type,
                shippingType: shippingType,
                dropshipper: $("#dropshipper").val(),
                shipmentStatus: shipmentStatus,


            },
            datatype: 'json',
            success: function(data) {
                $('#orders').html(data);
                $("#type").val(type);
                $("#shippingTypeexport").val(shippingType);
                $("#fromDateexport").val(fromDate);
                $("#toDateexport").val(toDate);
                $("#periodexport").val(period);
                $("#shipmentStatusexport").val(shipmentStatus);

                $("#dropshipperexport").val($("#dropshipper").val());


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });

    }
</script>
