<?php

class PaymentController
{
    private function success($message, $code = '')
    {
        message('success', $message . $code, true);
    }

    private function fail($message, $code = '')
    {
        message('fail', $message . $code, true);
    }

    private function openTransaction($info)
    {
        PaymentModel::open_transaction($info['price'], $info['userId'], $info['authority'], $info['invoiceHash']);
    }

    private function closeTransaction($info)
    {
        PaymentModel::close_transaction($info['reference'], $info['invoiceHash']);
        afterCloseTransaction($info);
    }

    public function createInvoice($userId, $price, $startDate, $endDate, $title = null)
    {
        //grantAdmin();
        PaymentModel::create_invoice($userId, $price, $startDate, $endDate, $title);
    }

    public function pay($invoiceHash)
    {
        if (isGuest()) {
            $this->fail("ابتدا عضو سایت شوید تا مرحله پرداخت قابل انجام باشد");
        }

        $invoice = PaymentModel::fetch_invoice_by_hash($invoiceHash);
        $payed = PaymentModel::is_invoice_payed($invoiceHash);
        if ($payed) {
            $this->success("این فاکتور قبلاً پرداخت شده و نیاز به پرداخت مجدد نیست");
        }
        $gateway = isset($_POST['gateway']) ? $_POST['gateway'] : 'zarinpal';

        $userId = $_SESSION['user_id'];
        $info['userId'] = $invoice['user_id'];
        $info['invoiceHash'] = $invoiceHash;

        if ($userId != $info['userId']) {
            $this->fail("این شماره فاکتور متعلق به شما نیست و قابلیت پرداخت برای آن وجود ندارد");
        }
        $info['price'] = $invoice['price'];
        $info['email'] = $invoice['email'];
        $info['mobile'] = $invoice['mobile'];
        $info['title'] = $invoice['title'];

        if ($gateway == 'zarinpal') {
            $this->zarinpalPaymentRequest($info);
        }
    }


    private function zarinpalPaymentRequest($info)
    {
        global $config;
        load_nusoap();

        //$client = new nusoap_client('https://ir.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentRequest', array(
                array(
                    'MerchantID' => $config['zarinpal']['merchantId'],
                    'Amount' => $info['price'],
                    'Description' => $info['title'],
                    'Email' => $info['email'],
                    'Mobile' => $info['mobile'] == null ? "" : $info['mobile'],
                    'CallbackURL' => fullBaseUrl() . '/payment/zarinpalVerify/' . $info['invoiceHash'],
                )
            )
        );

        $authority = $result['Authority'];

        $info['authority'] = $authority;
        $this->openTransaction($info);

        if ($result['Status'] == 100) {
            //header('Location: https://ir.zarinpal.com/pg/StartPay/' . $authority);
            header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $authority);
        } else {
            $this->fail('فرآیند پرداخت با خطا مواجه شد. کد خطا: ', $result['Status']);
        }
    }

    public function zarinpalVerify($invoiceHash)
    {
        global $config;
        load_nusoap();

        $invoice = PaymentModel::fetch_invoice_by_hash($invoiceHash);

        if ($_GET['Status'] == 'OK') {
            $authority = $_GET['Authority'];
            //$client = new nusoap_client('https://ir.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $result = $client->call('PaymentVerification', array(
                    array(
                        'MerchantID' => $config['zarinpal']['merchantId'],
                        'Authority' => $authority,
                        'Amount' => $invoice['price'],
                    )
                )
            );

            if ($result['Status'] == 100) {
                $info['invoiceHash'] = $invoiceHash;
                $info['reference'] = $result['RefID'];
                $info['authority'] = $authority;
                $this->closeTransaction($info);

                $this->success('فرآیند پرداخت موفقیت آمیز بود. سند رهگیری: ' . $result['RefID']);
            } else if ($result['Status'] == 101) {
                $this->success('فرآیند پرداخت، قبلا انجام شده و نیاز به تأیید مجدد نیست', $result['Status']);
            } else {
                $this->fail('فرآیند پرداخت با خطا مواجه شد. کد خطا: ', $result['Status']);
            }
        } else {
            $this->fail('فرآیند پرداخت توسط یوزر، لغو شد!');
        }
    }

    public function accounting()
    {
        if (isGuest()) {
            $this->fail("ابتدا وارد سایت شوید، تا این قسمت قابل نمایش باشد");
        }
        $userId = $_SESSION['user_id'];
        $data['invoices'] = PaymentModel::fetch_pending_invoices($userId);
        View::render('default', "/payment/accounting.php", $data);
    }
}