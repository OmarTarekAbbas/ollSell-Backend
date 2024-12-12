@extends($layout)
<style>
    /* Chat Container Styling */
    .chat-container {
        max-height: 400px;
        overflow-y: auto;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    /* Chat Bubble Styling */
    .chat-message {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .chat-bubble {
        background-color: #e8f4fd;
        padding: 10px 15px;
        border-radius: 15px;
        position: relative;
        max-width: 70%;
    }

    .chat-bubble::before {
        content: "";
        position: absolute;
        top: 10px;
        left: -10px;
        width: 10px;
        height: 10px;
        background-color: #e8f4fd;
        transform: rotate(45deg);
    }

    /* Username Styling */
    .chat-username {
        font-weight: bold;
        color: #0078d4;
    }

    /* Chat Text Styling */
    .chat-text {
        margin: 5px 0 0;
        color: #333;
    }

    /* Time Styling */
    small.text-muted {
        font-size: 0.85rem;
        color: #888;
    }

    /* User-Specific Colors */
    [data-user-id="1"] .chat-bubble {
        background-color: #dfffe0;
        border-left: 4px solid #47b858;
    }

    [data-user-id="2"] .chat-bubble {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    [data-user-id="3"] .chat-bubble {
        background-color: #fce4ec;
        border-left: 4px solid #e91e63;
    }

    [data-user-id="4"] .chat-bubble {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
    }

    /* Chat Container Scrollbar */
    .chat-container::-webkit-scrollbar {
        width: 8px;
    }

    .chat-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }
</style>

@section('title', 'Order Details')

@section('content')

<div class="card ">
    <!--begin::Card header-->
    <div class="card-header card-header-stretch border-bottom border-gray-200">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="fw-bold m-0">Withdrawal Request</h3>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Card header-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab panel-->
        <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel"
            aria-labelledby="kt_billing_months">
            <!--begin::Table container-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table table-row-bordered align-middle gy-4 gs-9">
                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                        <tr>
                            <td class="min-w-150px">ID</td>
                            <td class="min-w-150px">Dropshipper</td>
                            <td class="min-w-250px">Status</td>
                            <td class="min-w-150px">Amount</td>
                            <th class="min-w-50px">Total Amount Dropshipper</th>
                            <th class="min-w-50px">Withdraw Dropshipper</th>
                            <th class="min-w-50px">Balance Dropshipper</th>
                            <td class="min-w-150px">Reason</td>
                            <td class="text-muted">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-phone fs-2 me-2"></i>Image
                                </div>
                            </td>
                            <td class="min-w-150px">Date</td>
                            <td class="min-w-150px">Action</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <tr class="odd" id="row{{ $data->id }}">
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->dropshipper->first_name . ' ' . $data->dropshipper->second_name }}</td>
                            <td style="color: {{ status($data->status) }}" id="status{{ $data->id }}">
                                {{ $data->status }}
                            </td>
                            <td>{{ $data->amount }}</td>
                            <td>{{ $data->total_amount_dropshipper}}</td>
                            <td>{{ $data->withdraw_dropshipper}}</td>
                            <td>{{ $data->balance_dropshipper}}</td>
                            <td id="reason{{ $data->id }}">{{ $data->reason ?? '-' }}</td>

                            @if ($data->avatar)
                            <td class="fw-bold text-end">
                                <a target="_blank" href="{{ getFile($data->avatar->file??null,pathType()['ip'],getFileNameServer($data->avatar))  }}">
                                    صورة التحويل
                                </a>
                            </td>
                            @else
                            <td class="fw-bold text-end">
                                -------
                            </td>
                            @endif

                            <td>{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l d F Y') . ' in ' . date('h:i', strtotime($data->created_at)) . ' ' . date('a', strtotime($data->created_at)) }}
                                @if ($data->status === $data::PENDING_STATUS)
                            <td class="text-end" id="removebutton{{ $data->id }}">
                                @can('update_withdrawal_request')
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="language-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">

                                    <!-- inProgress Option -->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('withdrawalRequest.inProgress', ['id' => request()->route('id')]) }}"
                                            class="menu-link px-3">inProgress</a>
                                    </div>

                                    <!-- Rejected Option -->
                                    <div class="menu-item px-3">
                                        <a href="#"
                                            onclick="toggleActive({{ $data->id }}, 'rejected', '{{ $data->reason }}'); return false;"
                                            class="menu-link px-3">Rejected</a>
                                    </div>

                                </div>
                                @endcan
                            </td>
                            @elseif($data->status === $data::INPROGRESS_STATUS)
                            <td class="text-end" id="removebutton{{ $data->id }}">
                                @can('update_withdrawal_request')
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="language-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">

                                    <!-- Approved Option -->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('withdrawalRequest.upload.form', ['id' => request()->route('id')]) }}"
                                            class="menu-link px-3">Approved</a>
                                    </div>

                                    <!-- Rejected Option -->
                                    <div class="menu-item px-3">
                                        <a href="#"
                                            onclick="toggleActive({{ $data->id }}, 'rejected', '{{ $data->reason }}'); return false;"
                                            class="menu-link px-3">Rejected</a>
                                    </div>

                                </div>
                                @endcan
                            </td>
                            @elseif($data->status === $data::APPROVED_STATUS)
                            <td>Approved <i class="fa fa-check text-success"></i></td>
                            @elseif($data->status === $data::REJECTED_STATUS)
                            <td>Rejected <i class="fa fa-times text-danger"></i></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table container-->
        </div>
        <!--end::Tab panel-->
    </div>
    <!--end::Tab Content-->
</div>


<div class="card my-2">
    <div class="d-flex card-body flex-column py-2">
        <!--begin::Owner-->
        <div class="d-flex align-items-center fs-4 fw-bold mb-5">
            Bank Details
        </div>
        <!--end::Owner-->

        <!--begin::Wrapper-->
        <div class="d-flex align-items-center">
            <!--begin::Icon-->
            <i class="fa-solid fa-bank" style="font-size: 2rem; padding: 10px 20px"></i>
            <!--end::Icon-->

            <!--begin::Details-->
            <div>


                <div class="fs-6 fw-semibold text-gray-700">
                    Bank name:
                    <span>
                        {{ $data->dropshipper_payment->bank_name }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    Bank Address:
                    <span>
                        {{ $data->dropshipper_payment->bank_address }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    Swift No:
                    <span>
                        {{ $data->dropshipper_payment->swift_number }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    Beneficiary Name:
                    <span>
                        {{ $data->dropshipper_payment->beneficiary_name }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    Beneficiary Address:
                    <span>
                        {{ $data->dropshipper_payment->beneficiary_address }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    Beneficiary Mobile:
                    <span>
                        {{ $data->dropshipper_payment->beneficiary_mobile }}
                    </span>
                </div>

                <div class="fs-6 fw-semibold text-gray-700">
                    IBAN:
                    <span>
                        {{ $data->dropshipper_payment->iban }}
                    </span>
                </div>

            </div>
            <!--end::Details-->
        </div>
        <!--end::Wrapper-->
    </div>
</div>
@if(count($orders) > 0)
<div class="card my-2">
    <div class="d-flex card-body flex-column py-2">
        <!--begin::Owner-->
        <div class="d-flex align-items-center fs-4 fw-bold mb-5">
            Order Details
        </div>
        <!--end::Owner-->

        <!--begin::Wrapper-->
        <div class="d-flex align-items-center">
            <!--begin::Icon-->
            <!--end::Icon-->
            <!--begin::Details-->
            <div>


                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                    id="kt_orders_table"
                    aria-describedby="kt_orders_table_info" style="width: 1028px;">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">ID</th>
                            <th class="min-w-50px">customer name</th>
                            <th class="min-w-50px">customer phone</th>
                            <th class="min-w-50px">Status</th>
                            <th class="min-w-50px">Delivery Date</th>
                            <th class="min-w-50px">Grand Total</th>
                            <th class="min-w-50px">Net Profit</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600">
                        @forelse($orders as $order)
                        <tr class="odd" id="row{{ $order->id }}">
                            <td>{{ $order->id }}</td>
                            <td> {{ $order->customerName }}</td>
                            <td> {{ $order->customerPhone  }}</td>
                            <td> {{ getStatusText($order->status?->name?->value) }}</td>
                            <td>{{ $order->deliveryDate }}</td>
                            <td>{{ $order->grandTotal }}</td>
                            <td>{{ $order->net_profit }}</td>
                            <td>
                                @can('view_order')
                                <a href="{{ route('order.show', $order->id) }}"
                                    class="btn btn-light btn-active-light-primary btn-sm">View Details</a>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <h2>No Result</h2>
                        @endforelse
                    </tbody>

                </table>

            </div>
            <!--end::Details-->
        </div>
        <!--end::Wrapper-->
    </div>
</div>
@endif

<div class="card my-2">
    <div class="d-flex align-items-center fs-4 fw-bold mb-3 p-3 bg-light rounded">
        Chat for Withdrawal Request #{{ $withdrawalRequest->id }}
    </div>

    <!-- Display Existing Chats -->
    <div class="card mb-3">
        <div class="card-body chat-container" id="chat-container">
            @forelse ($chats as $chat)
            <div class="chat-message mb-3" data-user-id="{{ $chat->messagable_id }}"
                @if ($chat->messagable_type == 'App\Models\User') style="direction: rtl;" @endif>
                <div class="d-flex align-items-center">
                    <div class="chat-bubble"
                        @if ($chat->messagable_type == 'App\Models\User') style="background-color: #e8f4fd; border-left: 4px solid #4759b8;" @endif>
                        @if ($chat->messagable_type == 'App\Models\User')
                        <span class="chat-username">Admin</span>
                        @else
                        <span class="chat-username">{{ $chat->messagable->first_name . ' ' . $chat->messagable->second_name ?? 'Dropshipper' }}</span>
                        @endif
                        <p class="chat-text">{{ $chat->message }}</p>
                    </div>
                    <small class="text-muted ms-3">{{ $chat->created_at->format('d M Y, h:i A') }}</small>
                </div>
            </div>
            @empty
            <p>No messages yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Chat Form -->
    @if ($withdrawalRequest->canOpenChat())
    <div class="card">
        <div class="card-header">Send a Message</div>
        <div class="card-body">
            <form id="chat-form" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="3" placeholder="Type your message here..." required></textarea>
                    <span id="message-error" class="text-danger d-none">Please enter a message.</span>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
    @else
    <div class="d-flex align-items-center fs-4 fw-bold mb-3 p-3 bg-light rounded">
        The chat session for this request has been closed.
    </div>
    @endif
</div>





<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Reason</h4>
                <button type="button" class="btn-close" onclick="closebutton();"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="card-body border-top p-2">
                    <div class="row mb-6">
                        <label class="col-lg-12 col-form-label fw-semibold fs-6">Reason</label>
                        <div class="col-lg-12 d-flex align-items-center">
                            <textarea class="form-control" required name="reason" id="reason"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <input type="hidden" id="order_reuest" value="">
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="closebutton();">
                    Close
                </button>
                <button type="button" class="btn btn-primary" onclick="saveReason()">Save</button>

            </div>

        </div>
    </div>
</div>
@endsection

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection

@push('scripts')
<script>
    function toggleActive(id, status, reason) {
        let toggleActiveRoute;
        if (status == 'approved') {

            toggleActiveRoute = "{{ url('withdrawalRequest/approved') }}/" + id;
            $.get({
                url: toggleActiveRoute,
                data: {
                    id: id,
                },
                success: function(data) {
                    // var remove = $('#row' + id);
                    // remove.html("");
                    var removebutton = $('#removebutton' + id);
                    console.log(removebutton);
                    removebutton.html("");
                    removebutton.text("Approved");
                    var stat = $('#status' + id);
                    document.getElementById('status' + id).style.color = "#4f20db";
                    stat.text(status);


                },
            });
        } else {
            $("#order_reuest").val(id);
            $("#reason").val(reason);
            $('#myModal').toggle();
        }


    }

    function saveReason() {
        let reason = $("#reason").val();
        let id = $("#order_reuest").val();
        toggleActiveRoute = "{{ url('withdrawalRequest/refused') }}/" + id;
        $.get({
            url: toggleActiveRoute,
            data: {
                id: id,
                reason: reason,
            },
            success: function(data) {

                $('#myModal').toggle();
                $('#myModal').hide();
                var removebutton = $('#removebutton' + id);
                removebutton.html("");
                removebutton.text("Rejected");
                var stat = $('#status' + id);
                var reasonhtml = $('#reason' + id);
                reasonhtml.text(reason);
                document.getElementById('status' + id).style.color = "";

                stat.text("rejected");
            },
        });

    }

    function closebutton() {
        $('#myModal').hide();
    }


    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chat-form');
        const chatContainer = document.getElementById('chat-container');
        const messageField = document.getElementById('message');
        const messageError = document.getElementById('message-error');

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // منع الإرسال التقليدي

            const message = messageField.value.trim();
            if (!message) {
                messageError.classList.remove('d-none');
                return;
            }
            messageError.classList.add('d-none');

            const formData = new FormData(chatForm);

            fetch('{{ route("withdrawalRequest.chats.store", $withdrawalRequest->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error sending message');
                    return response.json();
                })
                .then(data => {
                    const newMessage = `
                    <div class="chat-message mb-3" data-user-id="${data.chat.messagable_id}" style="${data.chat.directionStyle}">
                        <div class="d-flex align-items-center">
                            <div class="chat-bubble" style="${data.chat.bubbleStyle}">
                                <span class="chat-username">${data.chat.username}</span>
                                <p class="chat-text">${data.chat.message}</p>
                            </div>
                            <small class="text-muted ms-3">${data.chat.created_at}</small>
                        </div>
                    </div>
                `;
                    chatContainer.insertAdjacentHTML('beforeend', newMessage);
                    messageField.value = ''; // تفريغ الحقل
                    chatContainer.scrollTop = chatContainer.scrollHeight; // تمرير لأسفل
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error sending message. Please try again.');
                });
        });
    });
</script>
@endpush