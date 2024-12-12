@extends($layout)


@section('title', $data->name?->value)
@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ $data->name?->value }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Bootstrap Carousel -->
                            <div id="productImageCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($data->logo as $key => $image)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img class="d-block w-100"
                                                src="{{ getFile($image->file, 'images', getFileNameServer($image)) }}"
                                                title="Click To Remove" onclick="deleteImage('{{ $image->id }}', this)"
                                                style="height: 300px; object-fit: cover;" alt="Image {{ $key + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#productImageCarousel" role="button"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#productImageCarousel" role="button"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $data->name?->value }}</td>
                                </tr>
                                <tr>
                                    <th>SKU</th>
                                    <td>{{ $data->sku }}</td>
                                </tr>
                                <tr>
                                    <th>Cost Price</th>
                                    <td>{{ $data->cost_price }}</td>
                                </tr>
                                <tr>
                                    <th>Stock</th>
                                    <td>{{ $data->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{!! $data->descriptionValue(1) !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('second-sidebar')
    @include('mastercatalog::layouts.sidebar')
@endsection


@push('scripts')
@endpush
