@extends('client.layouts.app')

@section('content')
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <div class="row">
        <!-- Thông tin liên hệ -->
        <div class="col-md-6">
            <h3 style="font-weight: bold;">LIÊN HỆ VỚI CHÚNG TÔI</h3>
            <p style="font-weight: bold;">TRƯỜNG CAO ĐẲNG FPT POLYTECHNIC</p>
            <ul style="list-style: disc; padding-left: 20px;">
                <li><b>Cơ sở 11:</b>Tòa Nhà FPT Polytechnic, Trịnh Văn Bô, Phường Xuân Phương, TP. Hà Nội.</li>
                <li><b>Hotline:</b> 0393063305</li>
                <li><b>Email:</b> powpow@gmail.com</li>
            </ul>
        </div>
        <!-- Form liên hệ -->
        <div class="col-md-6">
            <div style="background: #f44336; color: #fff; padding: 16px 20px; font-size: 22px; font-weight: 500; border-radius: 2px 2px 0 0;">
                LIÊN HỆ TƯ VẤN MUA HÀNG
            </div>
            <div style="border: 1px solid #f44336; border-top: none; padding: 24px;">
                @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @elseif(Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                <form method="POST" action="{{ route('client.contact.submit') }}">
                    @csrf
                    <input type="text" name="name" class="form-control mb-3" placeholder="Họ tên của bạn.." value="{{ old('name') }}" required>
                    <input type="text" name="phone" class="form-control mb-3" placeholder="Số điện thoại..." value="{{ old('phone') }}" required>
                    <textarea name="message" class="form-control mb-3" rows="4" placeholder="Nội dung cần tư vấn..." required>{{ old('message') }}</textarea>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-danger" style="width: 150px;">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
