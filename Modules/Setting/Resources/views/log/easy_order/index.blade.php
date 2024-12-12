@extends($layout)


@section('title', 'Easy Order Log')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  py-4 py-lg-8 ">

                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex flex-stack flex-wrap ">
                    <!--begin::Toolbar wrapper-->
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">


                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Order Faileds Listing
                            </h1>
                            <!--end::Title-->

                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ url('/') }}" class="text-muted text-hover-primary">
                                        Home
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    Easy Order
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    Order Faileds
                                </li>
                                <!--end::Item-->

                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--begin::Products-->
            <div class="card card-flush overflow-auto">
                @include('dashboard.error.error')
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->

                            <!--end::Svg Icon-->

                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->

                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center"
                             data-kt-customer-table-select="selected">
                            <button type="button" class="btn btn-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->

                                <!--end::Svg Icon-->Filter
                            </button>
                            <!--begin::Menu 1-->
                            {{--  <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                   id="kt-toolbar-filter" data-popper-placement="bottom-end">
                                  <!--begin::Header-->
                                  <div class="px-7 py-5">
                                      <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                  </div>
                                  <!--end::Header-->
                                  <!--begin::Separator-->
                                  <div class="separator border-gray-200"></div>
                                  <!--end::Separator-->
                                  <form action="{{ route('log.easy_order.index') }}" method="get">
                                      <!--begin::Content-->
                                      <div class="px-7 py-5">
                                          <!--begin::Input group-->
                                          <div class="mb-10">

                                              <!--begin::Label-->
                                              <label class="form-label fs-5 fw-semibold mb-3">Created Date:</label>
                                              <!--end::Label-->

                                              <!--begin::Options-->
                                              <!--end::Options-->

                                              <!--end::Options-->
                                          </div>
                                          <!--end::Input group-->

                                          <!--begin::Actions-->
                                          <div class="d-flex justify-content-end">
                                              <a href="{{ route('log.request.index') }}"
                                                 id="reset_filter"
                                                 class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                                                 data-kt-user-table-filter="reset">Reset
                                              </a>
                                              <button type="submit" class="btn btn-primary"
                                                      data-kt-menu-dismiss="true">Apply
                                              </button>
                                          </div>
                                          <!--end::Actions-->
                                      </div>
                                  </form>
                                  <!--end::Content-->
                              </div>--}}


                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->

                <div class="card-body pt-0" id="main_table" align="center">
                    choose date to download file
                    <form action="{{route('log.easy_order.download')}}" method="get">
                        <input type="date" name="date" id="date" required>
                    <br>
                    <br>
                        <input type="submit" value="download">
                    </form>
                </div>
                <!--end::Datatable-->
            </div>
            <!--end::Products-->
        </div>
@endsection
@section('second-sidebar')
    @include('setting::layouts.sidebar')
@endsection

