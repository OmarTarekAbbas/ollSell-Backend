@extends($layout)
@section('content')
    @push('styles')
        <style>

        </style>
    @endpush

    <validation-page></validation-page>
@endsection
@section('second-sidebar')
    @include('report::layouts.admin.sidebar')
@endsection
@push('scripts')
    <script></script>
@endpush
