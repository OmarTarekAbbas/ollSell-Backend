
@extends($layout)


@section('title', 'Order Details')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                <img src="/images/{{ Session::get('image') }}" width="300" />
                @endif

                <form action="{{ route('withdrawalRequest.upload.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="image">اختر صورة:</label>
                        <input type="file" required name="avatar" class="form-control" id="image">
                        <input type="hidden" name="id" value="{{request()->route('id')}}" class="form-control" id="image">
                    </div>
                    <button type="submit" class="btn btn-primary" >رفع الصورة</button>
                </form>
            </div>
        </div>
    </div>
    @endsection

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection
