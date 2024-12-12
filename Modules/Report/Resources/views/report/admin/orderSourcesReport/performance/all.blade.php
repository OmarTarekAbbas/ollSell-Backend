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
<style>
    .remark-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 0 auto;
    }

    .remark-box {
        width: 172px;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #e3d3f5 0%, #f0f0f0 100%);
        color: #3a3a3a;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .remark-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
    }

    .remark-count {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .remark-name {
        font-size: 18px;
        font-weight: 500;
    }
</style>
@endpush
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    {{-- <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->

                </div> --}}
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <button id="exportBtn" class="btn btn-success me-3" style="margin-bottom: 50px;">
                        Export Report
                    </button>
                    <div class="row" data-kt-user-table-filter="form">
                        <!--begin::Input group-->
                        <div class="col-md-12">

                            <label class="form-label fs-6 fw-semibold">Date:</label>
                            <div class="mb-10 d-flex justify-content-between">
                                <select id="period_type" class="form-select" data-control="select2" onchange="getFilter()"
                                    data-placeholder="Select an option" name="period">
                                    <option></option>
                                    <option value="today" @if($request->period == 'today') selected @endif>Today</option>
                                    <option value="this_week" @if($request->period == 'this_week') selected @endif>This
                                        Week
                                    </option>
                                    <option value="this_month" @if($request->period == 'this_month') selected @endif>This
                                        Month
                                    </option>
                                    <option value="this_year" @if($request->period == 'this_year') selected @endif>This
                                        Year
                                    </option>
                                    <option value="custom" @if($request->period == 'custom') selected @endif>Custom</option>
                                </select>
                            </div>

                            <div id="date_range" class="mb-10 d-flex justify-content-between d-none">
                                <label class="form-label fs-6 fw-semibold">From:</label>

                                <input type="text" name="fromDate" id="fromDate" autocomplete="off" value=""
                                    class="form-select" />
                                <label class="form-label fs-6 fw-semibold">To:</label>

                                <input type="text" name="toDate" id="toDate" autocomplete="off" value=""
                                    class="form-select" />
                            </div>
                        </div>
                        <!--end::Input group-->
                        <div class="col-md-4">
                            <div class="d-flex justify-content-end ">
                                <a href="{{ route('dashboard.report.orderSourcesReport', ['period' => 'this_month']) }}"
                                    id="reset_filter" class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                                    data-kt-user-table-filter="reset">Reset
                                </a>

                            </div>
                        </div>
                    </div>


                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->


        </div>
        <!--end::Products-->
        <br>
        <div class="card-body pt-0" id="data-table">
            @include('report::report.admin.orderSourcesReport.performance.mainContent')
        </div>
    </div>
</div>
@endsection
@section('second-sidebar')
@include('report::layouts.admin.sidebar')
@endsection
@push('scripts')
<script>
    let csrfToken = "{{ csrf_token() }}";

    $(document).ready(function() {
        $("#exportBtn").click(function() {
            console.log("Exporting report...");

            const period = $("#period_type").val() == null ? "{{ $request->period ?? 'this_month' }}" : $("#period_type").val();
            const toDate = $("#toDate").val();
            const fromDate = $("#fromDate").val();
            const source_platform = $("#source_platform").val();

            $.ajax({
                url: "{{ route('dashboard.report.exportOrderSourcesReport') }}",
                type: "GET",
                data: {
                    period: period,
                    toDate: toDate,
                    fromDate: fromDate,
                    source_platform: source_platform,
                },
                xhrFields: {
                    responseType: 'blob' // تعامل مع الاستجابة كـ Blob
                },
                success: function(response) {
                    // إنشاء رابط لتحميل الملف
                    const url = window.URL.createObjectURL(response);
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'OrderSourcesReport.xlsx'); // اسم الملف الذي سيتم تحميله
                    document.body.appendChild(link);
                    link.click();
                    link.remove(); // إزالة الرابط بعد النقر عليه
                },
                error: function(xhr, status, error) {
                    alert("Error exporting report: " + error); // في حالة حدوث خطأ
                }
            });
        });
    });

    $(document).ready(function() {
        $("#exportRemarkCancellationRates").click(function() {
            console.log("Exporting report...");

            const period = $("#period_type").val() == null ? "{{ $request->period ?? 'this_month' }}" : $("#period_type").val();
            const toDate = $("#toDate").val();
            const fromDate = $("#fromDate").val();
            const source_platform = $("#source_platform").val();

            $.ajax({
                url: "{{ route('dashboard.report.exportRemarkCancellationRates') }}",
                type: "GET",
                data: {
                    period: period,
                    toDate: toDate,
                    fromDate: fromDate,
                    source_platform: source_platform,
                },
                xhrFields: {
                    responseType: 'blob' // تعامل مع الاستجابة كـ Blob
                },
                success: function(response) {
                    // إنشاء رابط لتحميل الملف
                    const url = window.URL.createObjectURL(response);
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'exportRemarkCancellationRates.xlsx'); // اسم الملف الذي سيتم تحميله
                    document.body.appendChild(link);
                    link.click();
                    link.remove(); // إزالة الرابط بعد النقر عليه
                },
                error: function(xhr, status, error) {
                    alert("Error exporting report: " + error); // في حالة حدوث خطأ
                }
            });
        });
    });

    $(function() {
        $('input[name="fromDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD'));
            getFilter()
        });
        $('input[name="toDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD'));
            getFilter()
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
    });


    function getFilter() {
        var params = {
            period: $("#period_type").val() == null ? "{{ $request->period ?? 'this_month' }}" : $("#period_type").val(),
            toDate: toDate,
            fromDate: fromDate,
            source_platform: $("#source_platform").val(),

        };
        if ($("#fromDate").val()) {
            params.fromDate = $("#fromDate").val();
        }
        if ($("#toDate").val()) {
            params.toDate = $("#toDate").val();
        }
        var routeAll =
            "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->url() . '?' }}" +
            jQuery.param(params);
        $('#data-table').html(
            '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
        $.get({
            url: routeAll,
            success: function(data) {
                jQuery(document).ready(function() {
                    $('#data-table').html(data);
                });
            },
            error: function(textStatus) {
                var div = `<div class="alert alert-danger text-center mt-5 mb-5 p-5">` + textStatus.responseJSON.message + `</div>`
                $('#data-table').html(div);

            }
        });
    }

    $(document).ready(function() {
        $("#period_type").on('change', function() {
            if (this.value == "custom") {
                $("#date_range").removeClass('d-none');
            } else {
                $("#date_range").addClass('d-none');
            }
        });
    });
</script>
@endpush