<?php
include 'dbconn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    $user_secret_key = 'your_toyyibpay_user_secret_key';
    $category_code = 'your_category_code';
    $bill_name = 'Booking Payment';
    $bill_description = 'Payment for booking ID ' . $booking_id;
    $bill_amount = $amount * 100; // Amount in cents
    $bill_to = 'Customer';
    $bill_email = 'customer@example.com';
    $bill_phone = '0123456789';
    $bill_return_url = 'http://yourwebsite.com/payment_return.php'; // URL to handle return from ToyyibPay
    $bill_callback_url = 'http://yourwebsite.com/payment_callback.php'; // URL to handle callback from ToyyibPay

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://toyyibpay.com/index.php/api/createBill",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'userSecretKey' => $user_secret_key,
            'categoryCode' => $category_code,
            'billName' => $bill_name,
            'billDescription' => $bill_description,
            'billAmount' => $bill_amount,
            'billTo' => $bill_to,
            'billEmail' => $bill_email,
            'billPhone' => $bill_phone,
            'billReturnUrl' => $bill_return_url,
            'billCallbackUrl' => $bill_callback_url
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response_data = json_decode($response, true);
        $bill_code = $response_data[0]['BillCode'];
        $_SESSION['booking_id'] = $booking_id; // Store booking ID in session
        $_SESSION['amount'] = $amount; // Store amount in session
        header("Location: https://toyyibpay.com/$bill_code");
        exit();
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
