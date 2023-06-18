<div class="table-responsive">
    <table class="table">
        <tbody>

            <tr>
                <th scope="row" style="min-width: 150px;">محصول</th>
                <td>
                    {{ $stock_notify->product->title }}
                   ( <span>
                              @foreach($stock_notify->price->get_attributes as $attribute)
                            @if($attribute->group->type=='color')
                                <span>رنگ</span> :  {{$attribute->name}}
                                |
                            @else
                                <span>سایز</span> : {{$attribute->name}}
                            @endif
                        @endforeach
                        </span>)
                    <a href="{{ Route::has('front.products.show') ? route('front.products.show', ['product' => $stock_notify->product]) : '' }}" target="_blank">

                        <i class="feather icon-external-link"></i></a>
                </td>

            </tr>
            <tr>
                <th scope="row">نام</th>
                <td>{{ $stock_notify->name }}</td>

            </tr>

            <tr>
                <th scope="row">شماره موبایل</th>
                <td>{{ $stock_notify->mobile }}</td>
            </tr>
            <tr>
                <th scope="row">ایمیل</th>
                <td>{{ $stock_notify->email }}</td>
            </tr>

            <tr>
                <th scope="row">تاریخ ارسال</th>
                <td>{{ tverta($stock_notify->created_at) }}</td>
            </tr>

        </tbody>
    </table>
</div>
