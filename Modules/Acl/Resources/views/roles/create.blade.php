@extends($layout)

@section('title', 'Users')
@section('content')
@endsection

@section('second-sidebar')
@include('acl::layouts.sidebar')
@endsection


@push('scripts')
    <script>
        let route = "{{ route('user.index') }}";
        let csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('dashboard') }}/assets/js/users/list.js?v=1"></script>
@endpush
