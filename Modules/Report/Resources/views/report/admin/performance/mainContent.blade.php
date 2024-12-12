<div class="row">
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Average Order Create Time</span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderShipping < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderShipping == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderShippingCount) ?? "N/A"}} Orders</span>
                                        </span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->

                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    @if(isset($averageTimeShipping))

                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeShipping['day'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>
                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeShipping['hours'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeShipping['minutes'] }} </span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeShipping['second'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>

                        </div>

                    @else
                        <div class="col-lg-1" style="text-align: center">

                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>
                        </div>
                    @endif

                    <div class="col-lg-3">
                        @if($percentageChangeOrderShipping < 0)

                            <svg width="119" height="43" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @elseif($percentageChangeOrderShipping == 0)

                            <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeOrderShipping}}%

                            </span>
                        @else
                            <svg width="120" height="43" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderShipping}}%</span>

                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
<path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V2H21V21C21 21.6 20.6 22 20 22Z" fill="blue"/>
<path d="M12 14C9.2 14 7 11.8 7 9V5C7 4.4 7.4 4 8 4C8.6 4 9 4.4 9 5V9C9 10.7 10.3 12 12 12C13.7 12 15 10.7 15 9V5C15 4.4 15.4 4 16 4C16.6 4 17 4.4 17 5V9C17 11.8 14.8 14 12 14Z"
      fill="blue"/>
</svg>
 Average Order Processing Time</span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderDone < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderDone == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderDoneCount) ?? "N/A"}} Orders</span>
                                        </span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    @if(isset($averageTimeDone))

                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeDone['day'] }}</span><br><span

                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>
                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeDone['hours'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeDone['minutes'] }} </span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeDone['second'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>

                        </div>

                    @else
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>
                        </div>
                    @endif

                    <div class="col-lg-3">
                        @if($percentageChangeOrderDone < 0)

                            <svg width="119" height="43" viewBox="0 0 119 43" fill="none"

                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderDone}}%
                            </span>
                        @elseif($percentageChangeOrderDone == 0)

                            <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeOrderDone}}%

                            </span>
                        @else
                            <svg width="120" height="43" viewBox="0 0 120 43" fill="none"

                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base">

                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderDone}}%</span>

                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Order Create Time Reduction</span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderShipping < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderShipping == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderShippingCount) ?? "N/A"}} Orders</span>
                                        </span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    <div class="col-lg-12">
                        @if($percentageChangeOrderShipping < 0)
                            <svg width="350" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @elseif($percentageChangeOrderShipping == 0)

                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @else
                            <svg width="350" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderShipping}}%</span>
                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
<path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V2H21V21C21 21.6 20.6 22 20 22Z" fill="blue"/>
<path d="M12 14C9.2 14 7 11.8 7 9V5C7 4.4 7.4 4 8 4C8.6 4 9 4.4 9 5V9C9 10.7 10.3 12 12 12C13.7 12 15 10.7 15 9V5C15 4.4 15.4 4 16 4C16.6 4 17 4.4 17 5V9C17 11.8 14.8 14 12 14Z"
      fill="blue"/>
</svg>
 Order Processing Time Reduction</span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderDone < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderDone == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderDoneCount) ?? "N/A"}} Orders</span>
                                        </span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->

                    <div class="col-lg-12">
                        @if($percentageChangeOrderDone < 0)
                            <svg width="350" viewBox="0 0 119 43" fill="none"

                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base  text-black-400 pt-1 fw-semibold fs-1">

                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderDone}}%
                            </span>
                        @elseif($percentageChangeOrderDone == 0)

                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageChangeOrderDone}}%
                            </span>
                        @else
                            <svg width="350" viewBox="0 0 120 43" fill="none"

                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base  text-black-400 pt-1 fw-semibold fs-1">


                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderDone}}%</span>
                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg>
 Same Day Delivered Orders</span>
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($percentageRateOrderDeliveredSameDay < 0)
                    badge-light-danger
                    @elseif($percentageRateOrderDeliveredSameDay == 0)
                               badge-light-success
                    @else
                               badge-light-success
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentRateOrderDelivered[1] ?? 0) ?? "N/A"}} Orders</span>
                                        </span>
                </div>
                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <div class="col-lg-4">
                        @if($percentageRateOrderDeliveredSameDay < 0)
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredSameDay}}%
                            </span>
                        @elseif($percentageRateOrderDeliveredSameDay == 0)
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredSameDay}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredSameDay}}%
                            </span>
                        @endif
                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg>
 Next Day Delivered Orders</span>
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($percentageRateOrderDeliveredNextDay < 0)
                    badge-light-danger
                    @elseif($percentageRateOrderDeliveredNextDay == 0)
                               badge-light-success
                    @else
                               badge-light-success
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentRateOrderDelivered[2] ?? 0) ?? "N/A"}} Orders</span>
                                        </span>
                </div>
                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <div class="col-lg-4">
                        @if($percentageRateOrderDeliveredNextDay < 0)
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredNextDay}}%
                            </span>
                        @elseif($percentageRateOrderDeliveredNextDay == 0)
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredNextDay}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredNextDay}}%
                            </span>
                        @endif
                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg>
 More Day Delivered Orders</span>
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($percentageRateOrderDeliveredMoreDay < 0)
                    badge-light-danger
                    @elseif($percentageRateOrderDeliveredMoreDay == 0)
                               badge-light-success
                    @else
                               badge-light-success
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentRateOrderDelivered[3] ?? 0) ?? "N/A"}} Orders</span>
                                        </span>
                </div>
                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <div class="col-lg-4">
                        @if($percentageRateOrderDeliveredMoreDay < 0)
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredMoreDay}}%
                            </span>
                        @elseif($percentageRateOrderDeliveredMoreDay == 0)
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredMoreDay}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageRateOrderDeliveredMoreDay}}%
                            </span>
                        @endif
                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Average Order Confirmed to Shipped Time</span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderConfirmedShipped < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderConfirmedShipped == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderShippingCount) ?? "N/A"}} Orders</span>
                                        </span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->

                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    @if(isset($averageTimeConfirmedShipped))

                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeConfirmedShipped['day'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>
                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeConfirmedShipped['hours'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeConfirmedShipped['minutes'] }} </span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimeConfirmedShipped['second'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>

                        </div>

                    @else
                        <div class="col-lg-1" style="text-align: center">

                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>
                        </div>
                    @endif

                    <div class="col-lg-3">
                        @if($percentageChangeOrderShipping < 0)

                            <svg width="119" height="43" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @elseif($percentageChangeOrderShipping == 0)

                            <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeOrderShipping}}%

                            </span>
                        @else
                            <svg width="120" height="43" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderConfirmedShipped}}%</span>

                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Average Order Create To Perparing Time</span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderPerparing < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderPerparing == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderPerparingCount) ?? "N/A"}} Orders</span>
                                        </span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->

                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    @if(isset($averageTimePerparing))

                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimePerparing['day'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>
                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimePerparing['hours'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimePerparing['minutes'] }} </span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$averageTimePerparing['second'] }}</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>

                        </div>

                    @else
                        <div class="col-lg-1" style="text-align: center">

                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Day</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-1" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Hours</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><br><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Minutes</span></pre>

                        </div>
                        <div class="col-lg-1" style="text-align: center;margin: -35px 0 0 0;">
                                <span class="fs-2hx fw-bold  me-2 lh-1 ls-n2" style="
    color: blue;
"> : </span>
                        </div>
                        <div class="col-lg-2" style="text-align: center">
                            <pre><span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span><span
                                        class="text-gray-400 pt-1 fw-semibold fs-6">Seconds</span></pre>
                        </div>
                    @endif

                    <div class="col-lg-3">
                        @if($percentageChangeOrderPerparing < 0)

                            <svg width="119" height="43" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderPerparing}}%
                            </span>
                        @elseif($percentageChangeOrderPerparing == 0)

                            <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeOrderPerparing}}%

                            </span>
                        @else
                            <svg width="120" height="43" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderPerparing}}%</span>

                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Order Confirmed to Shipped Time Reduction</span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderConfirmedShipped < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderConfirmedShipped == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderShippingCount) ?? "N/A"}} Orders</span>
                                        </span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    <div class="col-lg-12">
                        @if($percentageChangeOrderShipping < 0)
                            <svg width="350" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @elseif($percentageChangeOrderShipping == 0)

                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageChangeOrderShipping}}%
                            </span>
                        @else
                            <svg width="350" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderConfirmedShipped}}%</span>
                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title">

                     <span class="text-gray-400 pt-1 fw-semibold ">  <svg width="24" height="24" viewBox="0 0 24 24"
                                                                          fill="none"
                                                                          xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                  fill="blue"/>
                            <path opacity="0.3"
                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                  fill="blue"/>
                        </svg> Order Create To Perparing Time Reduction</span>

                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>
                <div class="card-title">
                       <span class="badge
                    @if($lastPercentageChangeOrderPerparing < 0)
                      badge-light-success
                    @elseif($lastPercentageChangeOrderPerparing == 0)
                             badge-light-danger
                    @else
                            badge-light-danger
                    @endif fs-base">
                        <span class="text-black-400 pt-1 fw-semibold fs-1">{{number_format($currentOrderPerparingCount) ?? "N/A"}} Orders</span>
                                        </span>
                    <!--begin::Info-->

                    <!--end::Info-->
                    <!--begin::Subtitle-->

                    <!--end::Subtitle-->
                </div>

                <!--end::Title-->
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">

                    <!--begin::Amount-->
                    <div class="col-lg-12">
                        @if($percentageChangeOrderPerparing < 0)
                            <svg width="350" viewBox="0 0 119 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M112.698 19.6342C115.296 19.2793 117.8 20.9649 118.728 21.8521V42.0111H0V0.56015C1.48409 4.2271 6.18372 7.21388 8.34803 8.24891C8.73451 7.36174 10.1104 5.97185 12.522 7.5096C14.9337 9.04735 21.5657 10.7133 24.5803 11.354C25.4306 9.82609 27.6876 7.21388 29.9138 8.98821C32.6964 11.2061 37.798 12.9805 38.4937 12.0933C39.1894 11.2061 41.5083 8.2489 44.5228 10.0232C47.5374 11.7976 51.4795 13.7198 54.2622 13.7198C57.0449 13.7198 58.6681 11.6497 60.0594 10.4668C61.4508 9.28393 62.6102 11.6497 63.7697 12.5369C64.9291 13.424 65.3929 13.7198 66.3205 12.9805C67.248 12.2411 67.4799 12.389 68.4075 12.9805C69.335 13.5719 71.1901 14.9026 75.1323 16.677C79.0744 18.4513 79.5382 14.9026 81.6252 13.7198C83.7122 12.5369 87.4224 14.6069 90.437 16.0855C93.4516 17.5641 93.9153 17.4163 96.0023 17.712C98.0894 18.0077 99.2488 16.3812 100.872 16.0855C102.495 15.7898 103.887 17.5641 105.742 19.6342C107.597 21.7042 109.452 20.0778 112.698 19.6342Z"
                                      fill="url(#paint0_linear_10174_4377)"/>
                                <path d="M118.13 21.7042C117.201 20.8171 115.389 19.2793 112.788 19.6342C109.536 20.0778 107.678 21.7042 105.82 19.6342C103.961 17.5641 102.568 15.7898 100.942 16.0855C99.3158 16.3812 98.1544 18.0077 96.0639 17.712C93.9734 17.4163 93.5088 17.5641 90.4892 16.0855C87.4696 14.6069 83.7532 12.5369 81.6627 13.7198C79.5722 14.9026 79.1076 18.4513 75.1589 16.677C71.2102 14.9026 69.352 13.5719 68.4229 12.9805C67.4938 12.389 67.2615 12.2411 66.3324 12.9805C65.4033 13.7198 64.9387 13.424 63.7773 12.5369C62.6159 11.6497 61.4546 9.28393 60.0609 10.4668C58.6672 11.6497 57.0413 13.7198 54.254 13.7198C51.4666 13.7198 47.5179 11.7976 44.4983 10.0232C41.4787 8.24891 39.1559 11.2061 38.4591 12.0933C37.7623 12.9805 32.6522 11.2061 29.8649 8.98821C27.635 7.21388 25.3742 9.82609 24.5225 11.354C21.5029 10.7133 14.8597 9.04735 12.4441 7.5096C10.0284 5.97185 8.6502 7.36174 8.26307 8.24891C6.09515 7.21388 1.7593 4.40453 0.597918 0.56015"
                                      stroke="#12B76A" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4377" x1="59.3638" y1="1.29945" x2="59.3638"
                                                    y2="32.7938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#EBFFE3"/>
                                        <stop offset="1" stop-color="#EBFFE3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-success fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-down fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrderPerparing}}%
                            </span>
                        @elseif($percentageChangeOrderPerparing == 0)

                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            {{$percentageChangeOrderPerparing}}%
                            </span>
                        @else
                            <svg width="350" viewBox="0 0 120 43" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54595 20.3785C3.94878 20.0237 1.44437 21.7093 0.516815 22.5965V42.7555H119.244V1.3045C117.76 4.97145 113.061 7.95824 110.896 8.99326C110.51 8.1061 109.134 6.71621 106.722 8.25396C104.311 9.79171 97.6786 11.4576 94.664 12.0983C93.8138 10.5704 91.5567 7.95824 89.3306 9.73256C86.5479 11.9505 81.4463 13.7248 80.7507 12.8376C80.055 11.9505 77.7361 8.99326 74.7215 10.7676C71.707 12.5419 67.7648 14.4641 64.9822 14.4641C62.1995 14.4641 60.5763 12.3941 59.1849 11.2112C57.7936 10.0283 56.6341 12.3941 55.4747 13.2812C54.3152 14.1684 53.8515 14.4641 52.9239 13.7248C51.9963 12.9855 51.7644 13.1334 50.8369 13.7248C49.9093 14.3162 48.0542 15.647 44.1121 17.4213C40.17 19.1957 39.7062 15.647 37.6192 14.4641C35.5322 13.2812 31.8219 15.3513 28.8074 16.8299C25.7928 18.3085 25.329 18.1606 23.242 18.4564C21.155 18.7521 19.9956 17.1256 18.3723 16.8299C16.7491 16.5342 15.3578 18.3085 13.5026 20.3785C11.6475 22.4486 9.7924 20.8221 6.54595 20.3785Z"
                                      fill="url(#paint0_linear_10174_4385)"/>
                                <path d="M1.11414 22.4486C2.04324 21.5614 3.85501 20.0237 6.45651 20.3785C9.70839 20.8221 11.5666 22.4486 13.4248 20.3785C15.283 18.3085 16.6767 16.5342 18.3027 16.8299C19.9286 17.1256 21.09 18.7521 23.1805 18.4563C25.271 18.1606 25.7355 18.3085 28.7551 16.8299C31.7747 15.3513 35.4912 13.2812 37.5817 14.4641C39.6722 15.647 40.1367 19.1957 44.0854 17.4213C48.0341 15.647 49.8924 14.3162 50.8215 13.7248C51.7506 13.1334 51.9829 12.9855 52.912 13.7248C53.8411 14.4641 54.3056 14.1684 55.467 13.2812C56.6284 12.3941 57.7898 10.0283 59.1835 11.2112C60.5771 12.3941 62.2031 14.4641 64.9904 14.4641C67.7777 14.4641 71.7264 12.5419 74.746 10.7676C77.7656 8.99326 80.0884 11.9505 80.7852 12.8376C81.4821 13.7248 86.5922 11.9505 89.3795 9.73256C91.6094 7.95824 93.8702 10.5704 94.7219 12.0983C97.7415 11.4576 104.385 9.79171 106.8 8.25396C109.216 6.71621 110.594 8.1061 110.981 8.99326C113.149 7.95824 117.485 5.14888 118.646 1.3045"
                                      stroke="#F04438" stroke-linecap="round" stroke-linejoin="round"/>
                                <defs>
                                    <linearGradient id="paint0_linear_10174_4385" x1="59.8806" y1="2.04381" x2="59.8806"
                                                    y2="33.5381" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFE3E3"/>
                                        <stop offset="1" stop-color="#FFE3E3" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="badge badge-light-danger fs-base text-black-400 pt-1 fw-semibold fs-1">
                            <i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>{{$lastPercentageChangeOrderPerparing}}%</span>
                        @endif


                    </div>
                </div>

                <!--end::Badge-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
</div>
<script>
    var fromDate = "{{ $currentPeriod['from'] }}";
    var toDate = "{{ $currentPeriod['to'] }}";
</script>
