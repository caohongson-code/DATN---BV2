<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MomoTransaction;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    public function momo_payment(Request $request)
    {
        $requestId = $request->input('request_id');
        $amount = $request->input('total_momo');

        // ✅ orderId thật từ DB
        $orderIdRaw = $request->input('order_id');

        // ✅ Tạo orderId duy nhất gửi cho MoMo (VD: 33-1751378888)
        $orderId = $orderIdRaw . '-' . time();

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $redirectUrl = route('momo.redirect');
        $ipnUrl = route('momo.ipn');
        $orderInfo = "Thanh toán qua ATM MoMo";
        $extraData = ""; // hoặc mã voucher, ID user...
        $requestType = "payWithATM";

        // ✅ Chuỗi raw hash
        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId'     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId, // ✅ Gửi orderId duy nhất
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        }

        return back()->with('error', '❌ Không thể tạo thanh toán MoMo: ' . ($jsonResult['message'] ?? 'Lỗi không xác định'));
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ],
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function handleMomoIpn(Request $request)
    {

        // ✅ Lưu giao dịch lại
        MomoTransaction::create([
            'partner_code'  => $request->input('partnerCode'),
            'order_id'      => $request->input('orderId'),
            'request_id'    => $request->input('requestId'),
            'amount'        => $request->input('amount'),
            'order_info'    => $request->input('orderInfo'),
            'order_type'    => $request->input('orderType'),
            'trans_id'      => $request->input('transId'),
            'result_code'   => $request->input('resultCode'),
            'message'       => $request->input('message'),
            'pay_type'      => $request->input('payType'),
            'response_time' => now(),
            'extra_data'    => $request->input('extraData'),
            'signature'     => $request->input('signature'),
        ]);

        // ✅ Cập nhật đơn hàng nếu thanh toán thành công
        if ($request->input('resultCode') == 0) {
            $fullOrderId = $request->input('orderId');
            $realOrderId = explode('-', $fullOrderId)[0];

            DB::table('orders')->where('id', $realOrderId)->update([
                'payment_status_id' => 2,
                'updated_at' => now(),
            ]);
        }

        return response('IPN received', 200);
    }

    public function handleMomoRedirect(Request $request)
    {
       $fullOrderId = $request->input('orderId');
$realOrderId = explode('-', $fullOrderId)[0];

MomoTransaction::create([
    'partner_code'  => $request->input('partnerCode'),
    'order_id'      => $realOrderId, // ✅ Sửa chỗ này
    'request_id'    => $request->input('requestId'),
    'amount'        => $request->input('amount'),
    'order_info'    => $request->input('orderInfo'),
    'order_type'    => $request->input('orderType'),
    'trans_id'      => $request->input('transId'),
    'result_code'   => $request->input('resultCode'),
    'message'       => $request->input('message'),
    'pay_type'      => $request->input('payType'),
    'response_time' => now(),
    'extra_data'    => $request->input('extraData'),
    'signature'     => $request->input('signature'),
]);

// ✅ Cập nhật trạng thái đơn hàng nếu thanh toán thành công
if ($request->input('resultCode') == 0) {
    DB::table('orders')->where('id', $realOrderId)->update([
        'payment_status_id' => 2,
        'updated_at' => now(),
    ]);
}

return redirect()->route('momo.result', ['orderId' => $realOrderId]);
    }

public function retryPayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->payment_status_id == 2) {
            return redirect()->back()->with('info', 'Đơn hàng này đã được thanh toán.');
        }

        $requestId = uniqid() . time();
        $newOrderId = $order->id . '-' . time();

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $redirectUrl = route('momo.redirect');
        $ipnUrl = route('momo.ipn');
        $orderInfo = "Thanh toán lại đơn hàng #" . $order->id;
        $amount = (int) $order->total_amount; // đảm bảo là integer
        $extraData = "";
        $requestType = "payWithATM";

        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$newOrderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId'     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $newOrderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Log toàn bộ response để debug
        Log::info('MoMo retry response:', $jsonResult);

        MomoTransaction::create([
            'partner_code' => $partnerCode,
            'order_id'     => $order->id,
            'request_id'   => $requestId,
            'amount'       => $amount,
            'order_info'   => $orderInfo,
            'order_type'   => $requestType,
            'trans_id'     => $jsonResult['transId'] ?? null,
            'result_code'  => $jsonResult['resultCode'] ?? null,
            'message'      => $jsonResult['message'] ?? 'Đang tạo giao dịch',
            'pay_type'     => 'ATM',
            'response_time'=> now(),
            'extra_data'   => $extraData,
            'signature'    => $signature,
        ]);

        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        }

        $errorMessage = $jsonResult['message'] ?? 'Lỗi không xác định';
        return redirect()->back()->with('error', 'Không thể tạo thanh toán mới: ' . $errorMessage);
    }


public function convertToCod(Request $request, $id)
{
    $order = Order::findOrFail($id);

    if ($order->payment_status_id == 1 && $order->paymentMethod->code == 'momo') {
        $codMethod = PaymentMethod::where('code', 'cod')->first();

        $order->payment_method_id = $codMethod->id;
        $order->save();

        return redirect()->back()->with('success', 'Đã chuyển sang thanh toán khi nhận hàng.');
    }

    return redirect()->back()->with('error', 'Không thể chuyển trạng thái.');
}

}
