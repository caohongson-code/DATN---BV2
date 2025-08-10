@extends('client.user.dashboard')

@section('dashboard-content')
    {{-- Include Bootstrap Icons --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .promotion-container {
            padding: 10px 15px;
        }

        .promotion-title {
            color: #ee4d2d;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .promotion-card {
            display: flex;
            align-items: center;
            border: 1.5px solid #ee4d2d;
            border-radius: 12px;
            background-color: #fffaf5;
            transition: 0.3s ease-in-out;
            padding: 15px 20px;
            margin-bottom: 20px;
            width: 100%;
        }

        .promotion-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .promotion-icon {
            font-size: 2rem;
            color: #ee4d2d;
            margin-right: 20px;
        }

        .promotion-content {
            flex-grow: 1;
        }

        .promotion-code {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ee4d2d;
        }

        .promotion-desc {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 8px;
        }

        .promotion-details {
            font-size: 0.9rem;
            color: #444;
            margin-bottom: 10px;
        }

        .promotion-details span {
            display: inline-block;
            margin-right: 15px;
        }

        .promotion-actions {
            text-align: right;
            white-space: nowrap;
        }

        .btn-save {
            border: 1px solid #ee4d2d;
            background-color: transparent;
            color: #ee4d2d;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .btn-save:hover {
            background-color: #ee4d2d;
            color: #fff;
        }

        .btn-remove {
            border: 1px solid #999;
            background-color: #f8f8f8;
            color: #777;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .btn-remove:hover {
            background-color: #f1c0c0;
            color: #b02a37;
        }

        @media (max-width: 768px) {
            .promotion-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .promotion-actions {
                margin-top: 10px;
                width: 100%;
                text-align: left;
            }

            .promotion-details span {
                display: block;
                margin-bottom: 4px;
            }
        }
    </style>

    <div class="promotion-container mt-4">
        <h3 class="promotion-title"><i class="bi bi-gift-fill"></i> Mã Giảm Giá Dành Cho Bạn</h3>

        @forelse ($promotions as $promotion)
            <div class="promotion-card">
                <div class="promotion-icon"><i class="bi bi-ticket-perforated-fill"></i></div>

                <div class="promotion-content">
                    <div class="promotion-code">{{ $promotion->code }}</div>
                    <div class="promotion-desc">{{ $promotion->description }}</div>
                    <div class="promotion-details">
                        <span>🔻 Giảm:
                            <strong>
                                {{ $promotion->discount_type == 'percentage'
                                    ? $promotion->discount_value . '%'
                                    : number_format($promotion->discount_value) . 'đ' }}
                            </strong>
                        </span>
                        <span>📦 Đơn tối thiểu:
                            <strong>
                                {{ $promotion->min_order_amount ? number_format($promotion->min_order_amount) . 'đ' : 'Không yêu cầu' }}
                            </strong>
                        </span>
                        <span>⏳ HSD:
                            <strong>{{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}</strong>
                        </span>
                    </div>
                </div>

                <div class="promotion-actions ms-auto">
                    @if (in_array($promotion->id, $saved))
                        <a href="{{ route('client.promotions.unsave', $promotion->id) }}" class="btn btn-remove">❌ Bỏ
                            lưu</a>
                    @else
                        <a href="{{ route('client.promotions.save', $promotion->id) }}" class="btn btn-save">💾 Lưu mã</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center">
                😔 Hiện tại chưa có mã giảm giá nào.
            </div>
        @endforelse
    </div>
@endsection
