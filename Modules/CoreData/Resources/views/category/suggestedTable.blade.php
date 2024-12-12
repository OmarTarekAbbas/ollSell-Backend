<!--begin::Table-->
<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_category_table">
    <!--begin::Table head-->
    <thead>
        <!--begin::Table row-->
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px text-start">Name (English)</th>
            <th class="min-w-125px text-start">Name (Arabic)</th>
            <th class="min-w-125px text-center">Similar Categories</th>
            <th class=" text-center min-w-70px">Actions</th>
        </tr>
        <!--end::Table row-->
    </thead>
    <!--end::Table head-->

    <!--begin::Table body-->
    <tbody class="fw-semibold text-gray-600">
        @forelse($categories as $category)
            <tr>
                @foreach (language() as $lang)
                    <td class="text-center">
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="item_check" value="{{ $category->id }}" />
                            <!--begin::Thumbnail-->

                            <!--end::Thumbnail-->
                            <div class="ms-5">
                                <!--begin::Title-->
                                <p class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-category-filter="category_name">
                                    {{ Str::limit($category->nameValue($lang), 70) }}
                                </p>
                                <!--end::Title-->
                            </div>
                        </div>
                    </td>
                @endforeach

                <td class="text-center">
                    <!--end::Thumbnail-->
                    @php
                        $colors = ['success', 'info', 'warning', 'danger'];
                        $colorIndex = 0;
                    @endphp
                    <div class="ms-5">
                        @if($similars = $category->similarCategories())
                            @foreach($similars as $similar)
                            <a href="{{route('category.show', $similar)}}" class="badge badge-{{ $colors[$colorIndex % count($colors)] }}">{{ $similar->name->value }}</a>
                            @php $colorIndex++; @endphp
                            @endforeach
                        @else
                        -
                        @endif
                    </div>
                </td>


                <td class="text-center">
                    <a href="{{ route('suggestedCategories.show', $category->id) }}" title="View Category">
                        <i class="fas fa-eye text-primary" style="font-size: 25px; margin-right: 10px; cursor: pointer;"></i>
                    </a>
                    @permission('update_categories')
                    <i class="fas fa-times-circle text-danger" style="font-size:25px; cursor: pointer;" title="Reject" onclick="rejectCategory({{ $category->id }})"></i>
                    @endpermission
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger text-center">
                        <h3 class="text-center text-gray">No Records to display...</h3>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <div class="d-flex">
        @include('dashboard.layouts.table_length')
    </div>
    <div>
        {!! $categories->links() !!}
    </div>
</div>


@push('scripts')
    <script>
        // Function to handle category rejection
        function rejectCategory(categoryId) {
            Swal.fire({
                title: 'Rejection Reason',
                html: '<textarea id="rejectionReason" class="swal2-textarea" required></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const reason = Swal.getPopup().querySelector('#rejectionReason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please enter a rejection reason');
                        return false; // Prevent closing the modal if the reason is not entered
                    }
                    // Send AJAX request to reject the category with the reason
                    $.ajax({
                        method: 'POST',
                        url: '{{route('suggestedCategories.rejectCategoriesSupplier')}}', // Replace with your endpoint
                        data: {
                            _token: '{{ csrf_token() }}',
                            categoryId: categoryId,
                            reason: reason
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Category Rejected',
                                icon: 'success',
                                text: 'The category has been rejected.',
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    location.reload();
                                }
                            }).then((result) => {
                                if (result.isDismissed) {
                                    window.location.reload(true);
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error scenario
                            Swal.fire({
                                title: 'Error',
                                icon: 'error',
                                text: 'Failed to reject category suggestion.'
                            });
                        }
                    });
                }
            }).then((result) => {
                // if (result.isDismissed) {
                    // Swal.fire('Cancelled', 'No action taken', 'error');
                // }
            });
        }
    </script>
@endpush
