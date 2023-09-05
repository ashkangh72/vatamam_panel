<div class="table-responsive">
    <table class="table">
        <tbody>
            <tr>
                <th scope="row" class="text-nowrap" style="width: 200px;">آیدی</th>
                <td class="ltr text-center">{{ $sms->id }}</td>
            </tr>

            @if($sms->user)
                <tr>
                    <th scope="row" class="text-nowrap">کاربر دریافت کننده</th>
                    <td class="text-center">
                        {{ $sms->user->name ?: '--' }} <a class="float-right" href="{{ route('admin.users.show', ['user' => $sms->user]) }}" target="_blank"><i class="feather icon-external-link"></i></a>
                    </td>
                </tr>
            @endif

            <tr>
                <th scope="row" class="text-nowrap">موبایل</th>
                <td class="ltr text-center">{{ $sms->phone }}</td>
            </tr>

            <tr>
                <th scope="row" class="text-nowrap">نوع</th>
                <td class="rtl text-center">{{ $sms->type() }}</td>
            </tr>

            <tr>
                <th scope="row" class="text-nowrap">تاریخ ارسال</th>
                <td class="ltr text-center">{{ jdate($sms->created_at) }}</td>
            </tr>

            <tr>
                <th scope="row" class="text-nowrap">ip</th>
                <td class="ltr text-center">{{ $sms->ip }}</td>
            </tr>

            <tr>
                <th scope="row" class="text-nowrap">پاسخ پنل پیامکی</th>
                <td class="ltr text-center">{{ $sms->response }}</td>
            </tr>

            <tr>
                <th scope="row" class="text-nowrap">متن پیامک</th>
                <td>{{ $sms->message }}</td>
            </tr>
        </tbody>
    </table>
</div>
