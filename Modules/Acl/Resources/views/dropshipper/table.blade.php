<table class="table align-middle table-row-dashed fs-6 gy-5">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="w-10px pe-2">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_dropshipper_table .form-check-input" value="1" />
                </div>
            </th>
            <th>{{ __('ID') }}</th>
            <th class="min-w-125px">{{ __('Email') }}</th>
            <th class="min-w-125px">{{ __('Dropshupper Name') }}</th>
            <!-- <th class="min-w-125px" style="text-align:start !important">{{ __('Phone Verification') }}</th> -->
            <th class="min-w-125px">{{ __('Mobile Number') }}</th>

            <th class="min-w-125px">{{ __('Created Date') }}</th>

            <!-- <th class="text-end min-w-70px">{{ __('Actions') }}</th> -->
            @permission('update_dropshipper')
                <th class="min-w-125px" style="text-align:start !important">{{ __('Add Item feature') }}</th>
                <th class="min-w-125px" style="text-align:start !important">{{ __('Percentage') }}</th>
                <th class="min-w-125px" style="text-align:start !important">{{ __('Status') }}</th>
                <th class="min-w-125px" style="text-align:start !important">{{ __('Blocked') }}</th>
            @endpermission
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse($dropshippers as $dropshipper)
            <tr>
                <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="item_check"
                            value="{{ $dropshipper->id }}" />
                    </div>
                </td>
                <td><a href="{{ route('dropshipper.show', $dropshipper) }}">{{ $dropshipper->id }}</a></td>

                <td><a href="{{ route('dropshipper.show', $dropshipper) }}">{{ $dropshipper->email }}</a></td>
                <td>
                    <p>{{ $dropshipper->first_name ?? '-' }}</p>
                </td>

                <td>
                    <p>{{ $dropshipper->phone }}</p>
                </td>

                <td>
                    <p>{{ $dropshipper->created_at->diffForHumans() }}</p>
                </td>

                @permission('update_dropshipper')
                    <td>
                        <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="extra_product_feature_enabled"
                                {{ $dropshipper->extra_product_feature_enabled ? 'checked' : '' }}
                                id="toggle_{{ $dropshipper->id }}"
                                onclick="handleToggle({{ $dropshipper->id }}, this.checked)">
                            <label class="form-check-label" for="toggle_{{ $dropshipper->id }}">
                                {{ $dropshipper->extra_product_feature_enabled ? 'Enabled' : 'Disabled' }}
                            </label>
                            <span id="loader_{{ $dropshipper->id }}" class="spinner-border spinner-border-sm d-none"
                                role="status" aria-hidden="true"></span>
                        </div>
                    </td>
                    <td>
                        @if ($dropshipper->extra_product_feature_enabled && $dropshipper->product_price_percentage)
                            <span>{{ $dropshipper->product_price_percentage }}%</span>
                            <i class="fa fa-edit" style="cursor: pointer;"
                                onclick="openPercentageModal({{ $dropshipper->id }}, {{ $dropshipper->product_price_percentage }})"></i>
                        @else
                            <span>-</span>
                        @endif
                    </td>


                    <td>
                        <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="" name="notifications"
                                {{ $dropshipper->status ? 'checked' : '' }}
                                onclick="toggleActive({{ $dropshipper->id }})">
                            <label class="form-check-label" id="active-label-{{ $dropshipper->id }}">
                                {{ $dropshipper->status ? 'Active' : 'Inactive' }}</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="" name="blocked"
                                {{ $dropshipper->blocked ? 'checked' : '' }}
                                onclick="toggleBlocked({{ $dropshipper->id }})">
                            <label class="form-check-label" id="blocked-label-{{ $dropshipper->id }}">
                                {{ $dropshipper->blocked ? 'Blocked' : 'Not Blocked' }}</label>
                        </div>
                    </td>
                @endpermission
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    <div class="alert alert-danger text-center">
                        <h3 class="text-center text-gray">No Records to display...</h3>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Percentage Modal -->
<div class="modal fade" id="percentageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Product Price Percentage</h5>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" onClick="cancelModal()">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <input type="number" step="0.1" class="form-control" id="percentageInput"
                    placeholder="Enter percentage">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onClick="cancelModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePercentage()">Save</button>
            </div>
        </div>
    </div>
</div>



<div class="d-flex justify-content-between">
    <div>
        @include('dashboard.layouts.table_length')
    </div>
    <div>
        {!! $dropshippers->appends(request()->query())->links() !!}
    </div>
</div>

@push('scripts')
    <script>
        let dropshipperId = null;

        function handleToggle(id, isEnabled) {
            dropshipperId = id;

            // Show loading spinner next to the toggle and disable the toggle while processing
            $('#loader_' + id).removeClass('d-none');
            $('#toggle_' + id).prop('disabled', true);

            if (isEnabled) {
                // Open modal to set percentage if enabling the feature
                $('#percentageModal').modal('show');
            } else {
                // If disabling, confirm with the user to clear the fields
                if (confirm('Are you sure you want to disable this feature? This will clear the percentage as well.')) {
                    clearFields(id);
                } else {
                    // Hide loader and re-enable toggle if the user cancels
                    $('#loader_' + id).addClass('d-none');
                    $('#toggle_' + id).prop('disabled', false);
                }
            }
        }

        function openPercentageModal(id, currentPercentage) {
            dropshipperId = id;
            $('#percentageInput').val(currentPercentage); // Pre-fill with the current percentage
            $('#percentageModal').modal('show');
        }

        function savePercentage() {
            let percentage = parseFloat($('#percentageInput').val());

            if (isNaN(percentage) || percentage < 0.01) {
                alert('Please enter a valid percentage greater than 0.01%');
                return; // Prevent submission if the input is invalid
            }

            // Send percentage and toggle status to the backend
            $.post('/acl/dropshipper/update-feature', {
                id: dropshipperId,
                percentage: percentage,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                alert(response.message);
                location.reload(); // Reload the page to update the UI
            }).fail(function() {
                alert('An error occurred while saving.');
            }).always(function() {
                // Hide the spinner after the request completes
                $('#loader_' + dropshipperId).addClass('d-none');
                $('#toggle_' + dropshipperId).prop('disabled', false);
            });

            $('#percentageModal').modal('hide');
        }

        function cancelModal() {
            $('#percentageModal').modal('hide');
            // Hide the spinner after the request completes
            $('#loader_' + dropshipperId).addClass('d-none');
            $('#toggle_' + dropshipperId).prop('disabled', false);
        }

        function clearFields(id) {
            // Send request to clear the fields
            $.post('/acl/dropshipper/clear-feature', {
                id: id,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                alert(response.message);
                location.reload(); // Reload the page to update the UI
            }).fail(function() {
                alert('An error occurred while clearing the feature.');
            }).always(function() {
                // Hide the spinner after the request completes
                $('#loader_' + id).addClass('d-none');
                $('#toggle_' + id).prop('disabled', false);
            });
        }
    </script>
@endpush
