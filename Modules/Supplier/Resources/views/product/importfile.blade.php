@extends('supplier.dashboard2.layouts.app')


@section('title', 'master catalog')

@section('content')
    <!--begin::Products-->
<div class="card card-flush">
<!--begin::Card header-->
<div class="card-header border-0 pt-6">
    <!--begin::Card title-->

    <!--begin::Card title-->

        <!--start::Import Excel-->
        <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
            <div class="fw-bold me-5">

                <form action="{{ route('supplier.product.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <a> Import Excel</a>
                    <div class="form-group mt-4">
                        <input type="file" name="excelFile" class="form-control" accept=".xls,.xlsx,.csv,.xlm,.xla,.xlc,.xlt,.xlw">
                    </div>

                    <button class="w-100 btn btn-lg btn-primary mt-4" type="submit">Save</button>
                </form>

            </div>
        </div>
        <!--end::Import Excel-->
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->

</div>
<!--end::Products-->
@endsection
@push('scripts')
        <script>
            var routeAll = "{{ route('supplier.product.index',Request()->all()) }}";
            var route = "{{ route('supplier.product.index') }}";
            var toggleActiveRoute = "{{ route('supplier.product.changeStatus') }}";
            var csrfToken = "{{ csrf_token() }}";
            var deletePermission = {{permissionShow('delete_product') ? 1 : 0}};
            var updatePermission = {{permissionShow('update_product') ? 1 : 0}};
        </script>
        <script src="{{ asset('dashboard') }}/assets/js/product/list.js?v=1"></script>
@endpush

@section('second-sidebar')
@include('supplier::layouts.sidebar')
@endsection
