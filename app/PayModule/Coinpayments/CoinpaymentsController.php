<?php
namespace App\PayModule\Coinpayments;

/**
 * CoinPayments Payment Method for TokenLite Application
 * To run this application, required TokenLite v1.1.2+ version.
 *
 * CoinPayments Controller
 *
 * @version 1.0.3
 * @since 1.0.0
 * @package TokenLite
 * @author Softnio
 *
 */

use Illuminate\Http\Request;
use App\PayModule\Coinpayments\CoinpaymentsPay;
use App\Http\Controllers\Controller;

class CoinpaymentsController extends Controller
{
    private $instance;

    public function __construct()
    {
        $this->instance = new CoinpaymentsPay();
    }
    public function success(Request $request)
    {
        $test="test";
        error_log( "test1 1 " . json_encode($test) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
        
        if(method_exists($this->instance, 'coinpay_success')){
            return $this->instance->coinpay_success($request);
        }
    }

    public function callback(Request $request)
    {
        $test="test";
        error_log( "test1 2 " . json_encode($test) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
        
        if(method_exists($this->instance, 'coinpay_callback')){
            return $this->instance->coinpay_callback($request);
        }
    }

    public function cancel(Request $request, $name='Order has been canceled due to payment!')
    {
        $test="test";
        error_log( "test1 3 " . json_encode($test) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
        
        if(method_exists($this->instance, 'payment_cancel')){
            return $this->instance->payment_cancel($request, $name);
        }
    }
}
