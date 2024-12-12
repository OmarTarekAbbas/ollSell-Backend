<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <table class="table table-bordered table-hover text-center">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th style="color: black;">Rate Type</th>
                            <th style="color: black;">Website</th>
                            <th style="color: black;">TikTok</th>
                            <th style="color: black;">Salla</th>
                            <th style="color: black;">Easy Order</th>
                            <th style="color: black;">Total Count</th>
                            <th style="color: black;">Total Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result['result'] as $rateType => $data)
                        <tr class="">
                            <!-- تحديد اسم نوع المعدل بناءً على مفتاحه -->
                            <td>
                                @switch($rateType)
                                @case('orderAllRates')
                                <span class="badge bg-dark" style="font-size: 15px; display: flex;">All Rates</span>
                                @break
                                @case('orderConfirmationRates')
                                <span class="badge bg-success" style="font-size: 15px; display: flex;">Confirmation Rates</span>
                                @break
                                @case('deliveryRatesForConfirmedOrders')
                                <span class="badge bg-primary" style="font-size: 15px; display: flex;">Delivery Rates for Confirmed Orders</span>
                                @break
                                @case('deliveryRatesForTotalOrders')
                                <span class="badge bg-warning text-dark" style="font-size: 15px; display: flex;">Delivery Rates Based on Total Orders</span>
                                @break
                                @case('getOrderCancellationRates')
                                <span class="badge bg-danger" style="font-size: 15px; display: flex;">Cancellation Rates</span>
                                @break
                                @endswitch
                            </td>
                            <!-- عرض القيم لكل منصة -->
                            <td>{{ $data['platformCounts']['website'] ?? 0 }} ({{ $data['platformPercentages']['website'] ?? 0 }}%)</td>
                            <td>{{ $data['platformCounts']['tikTok'] ?? 0 }} ({{ $data['platformPercentages']['tikTok'] ?? 0 }}%)</td>
                            <td>{{ $data['platformCounts']['salla'] ?? 0 }} ({{ $data['platformPercentages']['salla'] ?? 0 }}%)</td>
                            <td>{{ $data['platformCounts']['easyOrder'] ?? 0 }} ({{ $data['platformPercentages']['easyOrder'] ?? 0 }}%)</td>
                            <td><strong>{{ $data['platformCounts']['totalCount'] ?? 0 }}</strong></td>
                            <td><strong>{{ $data['platformPercentages']['totalPercentage'] ?? 0 }}%</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <button id="exportRemarkCancellationRates" class="btn btn-success me-3">
                    Export Remark
                </button>
                <div class="col-md-4" style="display: inline-block;     padding-top: 20px;">
                    <label class="form-label fs-6 fw-semibold">Source platform :</label>
                    <div class="mb-10 d-flex justify-content-between">
                        <select id="source_platform" class="form-select" onchange="getFilter()"
                            data-placeholder="Select an option" name="source_platform">
                            <option value="">all</option>
                            @foreach(\Modules\Order\Enums\PlatformEnum::list() as $source_platform)
                            <option value="{{$source_platform}}"
                                @if(request('source_platform')==$source_platform) selected @endif>
                                {{ $source_platform }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="remark-container">
                    @forelse ($result['getRemarkCancellationRates'] as $remarks)
                    @foreach($remarks as $remark => $count)
                    <div class="remark-box">
                        <div class="remark-count">{{ $count }}</div>
                        <div class="remark-name">{{ $remark }}</div>
                    </div>
                    @endforeach
                    @empty
                    <h1>No Remark</h1>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var fromDate = "{{ $result['date']['currentPeriod']['from'] }}";
    var toDate = "{{ $result['date']['currentPeriod']['to'] }}";

    $(document).ready(function() {
        $("#exportRemarkCancellationRates").click(function() {

            const period = $("#period_type").val() == null ? "{{ $request->period ?? 'this_month' }}" : $("#period_type").val();
            const toDate = $("#toDate").val();
            const fromDate = $("#fromDate").val();
            const source_platform = $("#source_platform").val();

            $.ajax({
                url: "{{ route('dashboard.report.exportRemarkCancellationRates') }}",
                type: "GET",
                data: {
                    period: period,
                    toDate: toDate,
                    fromDate: fromDate,
                    source_platform: source_platform,
                },
                xhrFields: {
                    responseType: 'blob' // تعامل مع الاستجابة كـ Blob
                },
                success: function(response) {
                    // إنشاء رابط لتحميل الملف
                    const url = window.URL.createObjectURL(response);
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'exportRemarkCancellationRates.xlsx'); // اسم الملف الذي سيتم تحميله
                    document.body.appendChild(link);
                    link.click();
                    link.remove(); // إزالة الرابط بعد النقر عليه
                },
                error: function(xhr, status, error) {
                    alert("Error exporting report: " + error); // في حالة حدوث خطأ
                }
            });
        });
    });
</script>