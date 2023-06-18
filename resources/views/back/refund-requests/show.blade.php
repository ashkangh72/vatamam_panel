<div class="table-responsive">
    <table class="table">
        <tbody>
            <tr>
                <th scope="row" style="min-width: 150px;">محصول</th>
                <td>{{ $refund_request->orderItem->product->title }} <a href="{{ Route::has('front.products.show') ? route('front.products.show', ['product' => $refund_request->orderItem->product]) : '' }}" target="_blank"><i class="feather icon-external-link"></i></a></td>
            </tr>
            <tr>
                <th scope="row">کاربر</th>
                <td><a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $refund_request->orderItem->order->user]) : '' }}" target="_blank"><i class="feather icon-external-link"></i></a> {{ $refund_request->orderItem->order->user->fullname ?? '--' }}</td>
            </tr>

            <tr>
                <th scope="row">دلیل</th>
                <td>
                    @switch($refund_request->reason)
                        @case('stricken')
                        زدگی یا آسیب در زمان تحویل
                        @break
                        @case('change_color_size')
                        درخواست تعویض رنگ یا اندازه
                        @break
                        @case('wrong_color_size')
                        رنگ یا اندازه اشتباه دریافت شده
                        @break
                    @endswitch
                </td>
            </tr>

            <tr>
                <th scope="row">ایمیل</th>
                <td>
                    @switch($refund_request->status)
                        @case('waiting')
                        <span class="text-info">در حال بررسی</span>
                        @break
                        @case('waiting_to_receive')
                        <span class="text-warning">درانتظار دریافت محصول</span>
                        @break
                        @case('received')
                        <span class="text-success">محصول دریافت شده</span>
                        @break
                        @case('rejected')
                        <span class="text-danger">رد شده</span>
                        @break
                    @endswitch
                </td>
            </tr>

            <tr>
                <th scope="row">تاریخ ارسال</th>
                <td>{{ jdate($refund_request->created_at)->format('%d %B %Y H:i:s') }}</td>
            </tr>
            @switch($refund_request->status)
                @case('waiting')
                    <tr>
                        <th scope="row">عملیات</th>
                        <td class="text-center">
                            <button data-refund-request="{{ $refund_request->id }}" data-id="{{ $refund_request->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-accept" data-toggle="modal" data-target="#accept-modal">تایید</button>
                            <button data-refund-request="{{ $refund_request->id }}" data-id="{{ $refund_request->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-reject" data-toggle="modal" data-target="#reject-modal">رد</button>
                        </td>
                    </tr>
                @break
                @case('waiting_to_receive')
                    <tr>
                        <th scope="row">عملیات</th>
                        <td class="text-center">
                            <button data-refund-request="{{ $refund_request->id }}" data-id="{{ $refund_request->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-receive" data-toggle="modal" data-target="#receive-modal">دریافت شد</button>
                        </td>
                    </tr>
                @break
            @endswitch
        </tbody>
    </table>
</div>
