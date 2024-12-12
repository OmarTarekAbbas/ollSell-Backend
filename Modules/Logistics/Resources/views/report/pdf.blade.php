@extends('dashboard-demo1.layouts.app')
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

    <!--end::Products-->
    <br>
    <div class="card-body pt-0" id="data-table">
        @include('logistics::report.mainContent')
    </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openmodel(id){
        $('#'+id).modal('toggle');
        }
        let csrfToken = "{{ csrf_token() }}";

        $(function () {

            $('input[name="fromDate"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
                getFilter()
            });
            $('input[name="toDate"]').on('apply.daterangepicker', function (ev, picker) {
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
            $('input[name="fromDate"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            $('input[name="toDate"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });


        function getFilter() {
         
            var params = {
                period: $("#period_type").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $("#period_type").val(),
                city: $("#city").val(),
                supplier: $("#supplier").val(),
                statusId: $("#statusId").val(),
                dropshipper: $("#dropshipper").val()
            };
        
            if ($("#fromDate").val()) {
                params.fromDate = $("#fromDate").val();
            }
            if ($("#toDate").val()) {
                params.toDate = $("#toDate").val();
            }
            var routeAll ="{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->url() . '?' }}" +jQuery.param(params);
            $('#data-table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
            $.get({
                url: routeAll,
                success: function (data) {
                
                    jQuery(document).ready(function () {
                        $('#data-table').html(data);
                    });
                },
                error: function (textStatus) {
                   
                    var div = `<div class="alert alert-danger text-center mt-5 mb-5 p-5">` + textStatus.responseJSON.message + `</div>`
                    $('#data-table').html(div);

                }
            });
        }

        $(document).ready(function () {
            $("#period_type").on('change', function () {
                if (this.value == "thisCustom") {
                    $("#date_range").removeClass('d-none');
                } else {
                    $("#date_range").addClass('d-none');
                }
            });

            $('#city').select2({
                placeholder: "{{ 'select city' }}...",
                ajax: {
                    url: "{{ route('city.list') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                }
                            }),
                        };
                    },
                }
            });
            $('#supplier').select2({
                placeholder: "{{ 'select supplier' }}...",
                ajax: {
                    url: "{{ route('supplier.list') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                }
                            }),
                        };
                    },
                }
            });
            $('#dropshipper').select2({
                placeholder: "{{ 'select dropshipper' }}...",
                ajax: {
                    url: "{{ route('dropshipper.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
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

    </script>
@endpush
