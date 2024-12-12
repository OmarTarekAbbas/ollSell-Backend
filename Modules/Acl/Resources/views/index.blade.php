@extends('acl::layouts.master')

@section('content')
    <h1>Hello World</h1>
    <h3>
        Here We go
    </h3>
    <p>
        This view is loaded from module: {!! config('acl.name') !!}
    </p>
@endsection
