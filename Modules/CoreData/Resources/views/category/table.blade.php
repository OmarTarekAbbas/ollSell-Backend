<!--begin::Table-->
<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_category_table">
    <!--begin::Table head-->
    <thead>
        <!--begin::Table row-->
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px text-center">ID</th>
            <th class="min-w-125px text-start">Name</th>
            <th class="min-w-125px text-center">Parent</th>
            <th class="min-w-125px text-center">Commission</th>
            @permission('update_categories')
            <th class="min-w-125px text-start" style="text-align:start !important">Status</th>
            @endpermission
            <th class=" text-center min-w-70px">Actions</th>
        </tr>
        <!--end::Table row-->
    </thead>
    <!--end::Table head-->

    <!--begin::Table body-->
    <tbody class="fw-semibold text-gray-600">
        @forelse($categories as $category)
        <tr>
            <td class="text-center">
                <p>{{ $category->id }}</p>
            </td>

            <td class="text-center">
                <div class="d-flex align-items-center">
                    <input type="hidden" name="item_check" value="{{ $category->id }}" />
                    <!--begin::Thumbnail-->

                    <!--end::Thumbnail-->
                    <div class="ms-5">
                        <!--begin::Title-->
                        <p class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-category-filter="category_name"><a href="{{ route('category.show', $category->id) }}">{{ Str::limit($category->name->value, 30) }} </a></p>
                        <!--end::Title-->
                    </div>
                </div>
            </td>
            <td class="text-center">
                <!--end::Thumbnail-->
                <div class="ms-5">
                    <!--begin::Title-->
                    <p class="text-gray-800 text-hover-primary text-center fs-5 fw-bold" data-kt-ecommerce-category-filter="category_name">
                        @if($category->parent_id)
                        {{ $category->parent->name->value }}
                        @else
                        -
                        @endif
                    </p>
                    <!--end::Title-->
                </div>
            </td>

            <td class="text-center" data-category-id="{{ $category->id }}">
                <div class="ms-5">
                    <p class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-category-filter="category_name">
                        <span class="commission-value">
                            @if($category->commission)
                                {{ $category->commission }}%
                            @else
                                -
                            @endif
                        </span>
                        @permission('update_categories')
                        <input type="text" inputmode="numeric" pattern="[0-9]*" step="0.01" min="0.00" max="1000.00" class="commission-input form-input" style="display: none;width: 80px;
                        box-sizing: border-box;">

                        <input type="hidden" class="category-id" value="{{ $category->id }}">
                        <i class="ki-duotone ki-notepad-edit edit-commission">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <button class="save-commission" style="display: none">
                            <i class="ki-duotone ki-tablet-ok">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                        @endpermission
                    </p>
                </div>
            </td>
            @permission('update_categories')
            <td class="text-center">
                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="" name="notifications" {{ $category->status ? 'checked' : '' }} onclick="toggleActive({{ $category->id }})">
                    <label class="form-check-label" id="active-label-{{ $category->id }}"> {{ $category->status ? 'Active' : 'Inactive' }}</label>
                </div>
            </td>
            @endpermission


            <td class="text-center">
                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                    Actions
                    <span class="svg-icon fs-5 m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                            </g>
                        </svg>
                    </span>
                </a>
                <!--begin::Menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">


                    <div class="menu-item px-3">
                        <a href=" {{ route('category.show', $category->id) }}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                            View
                        </a>
                    </div>
                    @permission('update_categories')

                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('category.edit', $category->id) }}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                            Edit
                        </a>
                    </div>
                    @endpermission
                    
                    <!--end::Menu item-->
                    @permission('delete_categories')

                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" data-kt-category-table-filter="delete_row" class="menu-link px-3">Delete</a>
                    </div>
                    <!--end::Menu item-->
                    @endpermission
                </div>
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
$(document).ready(function() {

    // $('.commission-input').on('input', function() {
    //     // Remove any non-numeric or non-decimal characters
    //     this.value = this.value.replace(/[^0-9.]/g, '');

    //     // Ensure there is only one decimal point
    //     if (this.value.split('.').length > 2) {
    //         this.value = this.value.slice(0, this.value.lastIndexOf('.'));
    //     }
    // });

    document.addEventListener('input', function (e) {
        if (e.target && e.target.id === 'commission-input') {
            // Remove any non-numeric or non-decimal characters
            e.target.value = e.target.value.replace(/[^0-9.]/g, '');

            // Ensure there is only one decimal point
            if (e.target.value.split('.').length > 2) {
                e.target.value = e.target.value.slice(0, e.target.value.lastIndexOf('.'));
            }

            // Ensure the value is within the specified range
            const value = parseFloat(e.target.value);
            if (isNaN(value) || value < 0.00 || value > 1000.00) {
            e.target.setCustomValidity('Please add a value from 0.00 to 1000.00');
            } else {
            e.target.setCustomValidity('');
            }
        }
    });

    $('.edit-commission').click(function() {
        let row = $(this).closest('tr');
        let commissionValue = row.find('.commission-value');
        let commissionInput = row.find('.commission-input');
        let saveButton = row.find('.save-commission');
        let commission = parseFloat(commissionValue.text().replace('%', ''));
        if (isNaN(commission)) {
            commission = null;
        }

        let categoryId = row.data('category-id'); // Access the data attribute correctly

        if (commissionValue.text() === '-') {
            commissionValue.text('0.00%');
            commissionInput.val('');
        } else {
            commissionInput.val(commission);
        }

        commissionValue.hide();
        commissionInput.show();
        saveButton.show();
        $(this).hide();
    });

    $('.save-commission').click(function() {
        let row = $(this).closest('tr');
        let commissionValue = row.find('.commission-value');
        let commissionInput = row.find('.commission-input');
        let saveButton = row.find('.save-commission');
        let commission = commissionInput.val();
        let categoryId = row.find('.category-id').val();

        // Validate the commission input
        if (isNaN(commission) || commission < 0 || commission > 1000) {
            alert('Please add a value from 0.00 to 1000.00');
            return;
        }
        let updateUrl = "{{ route('category.changeCommission') }}";

        // Send the updated commission to the server
        $.ajax({
            url: updateUrl,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category_id: categoryId, // Use the correct category ID
                commission: commission
            },
            success: function(data) {
                // Update the commission value on success
                commissionValue.text(commission + '%');
            },
            error: function(xhr, status, error) {
                let errorMessage = "Failed to update commission"; // Default error message

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message; // Use the specific error message from the response
                }

                Swal.fire({
                    text: errorMessage,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
            }
        });

        commissionInput.hide();
        commissionValue.show();
        saveButton.hide();
        row.find('.edit-commission').show();
    });
});


</script>
@endpush
