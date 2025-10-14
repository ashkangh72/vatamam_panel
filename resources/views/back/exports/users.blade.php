<table>
    <thead>
    <tr>
        <th>شناسه</th>
        <th>نام</th>
        <th>نام کاربری</th>
        <th>شماره تماس</th>
        <th>ایمیل</th>
        <th>تاریخ ثبت نام</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ tverta($user->created_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
