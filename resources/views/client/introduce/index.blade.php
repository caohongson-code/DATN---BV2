@extends('client.layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #ff6a00, #ff9800);">
        <div class="container">
            <h1 class="fw-bold display-4">Chào mừng đến với PowPow</h1>
            <p class="lead mt-3 mx-auto" style="max-width: 700px;">
                PowPow tự hào là đơn vị cung cấp các sản phẩm công nghệ & dịch vụ chất lượng cao,
                luôn đặt khách hàng làm trung tâm. Chúng tôi không chỉ mang đến sản phẩm,
                mà còn là giải pháp giúp bạn tối ưu hiệu quả và nâng cao trải nghiệm.
            </p>
            <a href="{{ route('client.categories') }}" class="btn btn-light btn-lg mt-3 fw-bold">Khám phá sản phẩm</a>
        </div>
    </section>

    {{-- Sứ mệnh & Tầm nhìn --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-4">
                <h2 class="fw-bold">Sứ mệnh & Tầm nhìn</h2>
                <p class="text-muted">Những giá trị cốt lõi tạo nên thương hiệu PowPow</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3 fs-1 text-warning">🚀</div>
                            <h5 class="fw-bold">Sứ mệnh</h5>
                            <p>Đem đến cho khách hàng trải nghiệm mua sắm hiện đại,
                               với sản phẩm chính hãng, chất lượng và dịch vụ tận tâm.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3 fs-1 text-warning">🌍</div>
                            <h5 class="fw-bold">Tầm nhìn</h5>
                            <p>Trở thành thương hiệu được yêu thích hàng đầu tại Việt Nam,
                               dẫn đầu về sự sáng tạo và uy tín trong từng sản phẩm.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3 fs-1 text-warning">💡</div>
                            <h5 class="fw-bold">Giá trị cốt lõi</h5>
                            <p>Chính trực – Chất lượng – Đổi mới – Khách hàng là trung tâm.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Số liệu nổi bật --}}
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2 class="fw-bold text-warning">5000+</h2>
                    <p>Khách hàng tin tưởng</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold text-warning">100+</h2>
                    <p>Sản phẩm chất lượng</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold text-warning">10+</h2>
                    <p>Năm kinh nghiệm</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold text-warning">99%</h2>
                    <p>Khách hàng hài lòng</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonial --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-4">
                <h2 class="fw-bold">Khách hàng nói gì?</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 shadow-sm bg-white rounded">
                        <p>"Sản phẩm chất lượng, dịch vụ hỗ trợ cực kỳ tận tâm. Mình rất hài lòng!"</p>
                        <h6 class="fw-bold mt-3">Nguyễn Văn A</h6>
                        <small class="text-muted">Khách hàng thân thiết</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 shadow-sm bg-white rounded">
                        <p>"Giao hàng nhanh, giá cả hợp lý, chắc chắn sẽ tiếp tục ủng hộ PowPow."</p>
                        <h6 class="fw-bold mt-3">Trần Thị B</h6>
                        <small class="text-muted">Doanh nghiệp SME</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 shadow-sm bg-white rounded">
                        <p>"Mình đã thử nhiều nơi nhưng PowPow vẫn là lựa chọn số 1 vì uy tín và chất lượng."</p>
                        <h6 class="fw-bold mt-3">Lê Văn C</h6>
                        <small class="text-muted">Khách hàng lâu năm</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #ff9800, #ff6a00);">
        <div class="container">
            <h2 class="fw-bold mb-3">Sẵn sàng đồng hành cùng bạn</h2>
            <p class="lead">Khám phá ngay những sản phẩm và dịch vụ chất lượng tại PowPow</p>
            <a href="{{ route('client.categories') }}" class="btn btn-light btn-lg fw-bold">Bắt đầu ngay</a>
        </div>
    </section>
@endsection
