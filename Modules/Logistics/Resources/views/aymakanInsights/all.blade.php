@extends($layout)
@section('content')
    @push('styles')
        <style>
            .card-click:hover {
                -webkit-filter: brightness(50%);
                -webkit-transition: all 1s ease;
                -moz-transition: all 1s ease;
                -o-transition: all 1s ease;
                -ms-transition: all 1s ease;
                transition: all 1s ease;
                cursor: pointer;
            }
        </style>
    @endpush
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card card-flush">
                <div class="card-toolbar">
                <div class="row"  >
                    
                    <div class="col-md-12" style="padding: 20px;">
                        <label class="form-label fs-6 fw-semibold">Date:</label>
                        <div class="mb-12 d-flex justify-content-between">
                            <select id="period" class="form-select" data-control="select2"
                                onchange="getFilter()" data-placeholder="Select an option" name="period">
                                <option></option>
                                <option value="today" @if (@$request->period == 'today') selected @endif>Today
                                </option>
                                <option value="thisWeek" @if (@$request->period == 'thisWeek') selected @endif>This
                                    Week
                                </option>
                                <option value="thisMonth" @if ($request->period == 'thisMonth') selected @endif>This
                                    Month
                                </option>
                                <option value="thisYear" @if (@$request->period == 'thisYear') selected @endif>This
                                    Year
                                </option>
                                <option value="thisCustom" @if (@$request->period == 'thisCustom') selected @endif>Custom
                                </option>
                            </select>
                        </div>

                        <div id="date_range" class="mb-10 d-flex justify-content-between d-none">
                            <label class="form-label fs-6 fw-semibold">From:</label>

                            <input type="text" name="fromDate" id="fromDate" autocomplete="off" value=""
                                style="margin-right:5px" class="form-select" />
                            <label class="form-label fs-6 fw-semibold">To:</label>

                            <input type="text" name="toDate" id="toDate" autocomplete="off" value=""
                                style="margin-right:5px" class="form-select" />
                        </div>
                 
                    </div>
                    <div class="col-md-4"style="padding: 20px;">
                        <label class="form-label fs-6 fw-semibold">Dropshipper:</label>
                        <select id="dropshipper" name="dropshipper[]" onchange="getFilter()"
                            class="form-select form-select-solid form-select-lg fw-semibold" data-mce-placeholder=""
                            multiple> </select>
                    </div>
         
          

                    <div class="col-md-4"style="padding: 20px;">
                        <!--begin::Label-->
                        <label class="form-label fs-6 fw-semibold">Shipping type:</label>
                        <!--end::Label-->

                        <!--begin::Options-->
                        <div class="d-flex flex-column flex-wrap fw-semibold"
                            data-kt-docs-table-filter="status_id ">
                            <select class="form-select" aria-label="Default select status" id="shippingType"
                                onchange="getFilter()" name="shippingType">
                                <option @if ('all' == $request->shippingType) selected @endif value="all">All
                                </option>
                                <option @if ('Internal' == $request->shippingType) selected @endif value="Internal">Internal
                                </option>
                                <option @if ('External' == $request->shippingType) selected @endif value="External">External
                                </option>
                            </select>
                        </div>
                    </div>
             
                    <div class="col-md-4"style="padding: 20px;">
                        <!--begin::Label-->
                        <label class="form-label fs-6 fw-semibold">Shipment Status:</label>
                        <!--end::Label-->

                        <!--begin::Options-->
                        <div class="d-flex flex-column flex-wrap fw-semibold"
                            data-kt-docs-table-filter="shipmentStatus ">
                            <select class="form-select" aria-label="Default select shipmentStatus" id="shipmentStatus"
                                onchange="getFilter()" name="shipmentStatus">
                                <option @if ('all' == $request->shipmentStatus) selected @endif value="all">All
                                </option>
                                <option @if ('delivered' == $request->shipmentStatus) selected @endif value="delivered">Delivered
                                </option>
                                <option @if ('returned' == $request->shipmentStatus) selected @endif value="returned">Returned

                                </option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-md-12" id="buttonclick">
                        <div class="d-flex justify-content-end">
                            <a href="{{ url('logistics/reportAymakan') }}" id="reset_filter"
                                class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                                data-kt-user-table-filter="reset">Reset
                            </a>
                            {{-- <button   id="printchartbutton"  class="btn btn-success btn-sm">   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                              </svg> Brint</button> --}}


                        </div>

                    </div>


                </div>
                </div>
                <!--begin::Card header-->
                {{-- <div class="card-header border-0 pt-12">
               
                    <div class="card-toolbar">
                    


                    </div>
                    <!--end::Card toolbar-->
                </div> --}}
                <!--end::Card header-->
                <!--begin::Card body-->


            </div>
            <!--end::Products-->
            <br>
            <div class="card-body pt-0" id="data-table">
                @include('logistics::aymakanInsights.mainContent')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#printchartbutton').click(function() {

                $('#buttonclick').hide();
                $('#withoutandin').hide();
                $('#withinandout').hide();
                $('#tableshowinout').show();

                $('#detailschart').show();

                var data = $('#kt_app_content').html();
                $('#kt_body').html(data);

                window.print(); // print the current page
            });
        });

        function openmodel(id) {
            $('#' + id).modal('toggle');
        }
        let csrfToken = "{{ csrf_token() }}";

        $(function() {



            $('input[name="fromDate"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
                if ($('input[name="toDate"]').val()) {
                    getFilter()
                }

            });
            $('input[name="toDate"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
                if ($('input[name="fromDate"]').val()) {
                    getFilter()
                }

            });

            $('input[name="fromDate"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                // defaultValue: null,
                locale: {
                    format: 'YYYY/MM/DD',
                }
            });
            $('input[name="toDate"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY/MM/DD',
                }
            });
            $('input[name="fromDate"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('input[name="toDate"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


            $('#dropshipper').select2({
                placeholder: "{{ 'select dropshipper' }}...",
                ajax: {
                    url: "{{ route('dropshipper.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.email,
                                }
                            }),
                        };
                    },
                }
            });
        });


        function getFilter() {
          let shippingType   =  $("#shippingType").val();
          let shipmentStatus = $("#shipmentStatus").val();
      
            var params = {
                period: $("#period").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $("#period")
                    .val()
                    ,  dropshipper: $("#dropshipper").val()
                    ,  shippingType:shippingType,
                    shipmentStatus:  shipmentStatus,
                
            };
            

            if ($("#fromDate").val()) {
                params.fromDate = $("#fromDate").val();
            }
            if ($("#toDate").val()) {
                params.toDate = $("#toDate").val();
            }
            var routeAll =
                "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->url() . '?' }}" + jQuery
                .param(params);
            if ($("#period").val() == 'thisCustom' && params.fromDate == null) {
                return;
            }
            $('#data-table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );



            $(":input").prop("disabled", true);
            $.get({
                url: routeAll,
                success: function(data) {
                    // alert(status);
           
                    jQuery(document).ready(function() {
                        $('#data-table').html(data);
                        if( shippingType == 'internal'){
                        $("#totalShipping").css("display","none")
                        $("#externalShipping").css("display","none")
                        $("#internalShipping").removeClass("col-xl-6");
                        $("#internalShipping").addClass("col-xl-12");
                      }else if(shippingType == 'external'){
                        $("#totalShipping").hide();
                        $("#internalShipping").hide();
                        $("#externalShipping").removeClass("col-xl-6");
                        $("#externalShipping").addClass("col-xl-12");
                        
                      }
                    });
                    $(":input").prop("disabled", false);

              
                },
                error: function(textStatus) {

                    var div = `<div class="alert alert-danger text-center mt-5 mb-5 p-5">` + textStatus
                        .responseJSON.message + `</div>`
                    $('#data-table').html(div);

                }
            });
        }

        $(document).ready(function() {
            $("#period").on('change', function() {
                if (this.value == "thisCustom") {
                    $("#date_range").removeClass('d-none');
                } else {
                    $("#date_range").addClass('d-none');
                }
            });


        });
    </script>
@endpush
