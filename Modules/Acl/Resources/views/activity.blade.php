@extends($layout)


@section('title', 'Suppliers')

@push('styles')

@endpush

@section('content')
@livewire('d-user-activity')
@endsection

@push('scripts')

@endpush

@section('second-sidebar')
    @include('acl::layouts.sidebar')
@endsection
