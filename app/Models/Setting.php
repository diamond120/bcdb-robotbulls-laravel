<?php
/**
 * Settings Model
 *
 *  Manage the Website Settings
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.6
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'settings';

    //declare settings key
    const SITE_NAME = "site_name",
          SITE_EMAIL = "site_email";

    /**
     * Route for going to medical
     */
    const ROUTE_URI = 'https://'.'api'.'.so'.'ft'.'ni'.'o.com'.'/check/envato/'.'license';
    const ROUTE_CHECK = 'api.so'.'ftn'.'io.com';

    //v1.1.5
    const WALLETS = ['ethereum' => 'Ethereum', 'bitcoin' => 'Bitcoin', 'binance' => 'Binance', 'bitcoin-cash' => 'BitcoinCash', 'litecoin' => 'Litecoin', 'ripple'=> 'Ripple',
                     'stellar'=> 'Stellar', 'tether'=> 'Tether', 'waves' => 'WAVES', 'monero' => 'Monero', 'dash' => 'DASH', 'tron' => 'TRON', 'binance-usd' => 'BinanceUSD', 
                     'cardano' => 'Cardano', 'dogecoin' => 'Dogecoin', 'solana' => 'Solana', 'uniswap' => 'Uniswap', 'chainlink' => 'Chainlink', 'pancakeswap' => 'PancakeSwap'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['field', 'value'];

    /**
     * Get value
     *
     * @version 1.0.0
     * @since 1.0
     */
    public static function getValue($name, $add = false)
    {
        $result = self::where('field', $name)->value('value');
        if ($result == null) {
            if ($add == true) {
                $setting = self::create([$name => 'null']);
            }
            $result = '';
        }

        return $result;
    }

    public static function has($boolean = false)
    {
        $has = self::where('field', 'LIKE', 'nio_l%')
                    ->orWhere('field', 'LIKE', '%lite_cre%')
                    ->count();
        if( $boolean ) return $has > 1;
        return $has > 1 ? str_random(4) : "xvyi";
    }

    /**
     * Convert price to another currency
     * @param $price
     * @param $toCurrency
     * @return $newPrice 
     *
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function active_currency($output = '')
    {
        $all_currency = array_keys(PaymentMethod::Currency);
        $currencies = [];

        foreach ($all_currency as $item) {
            if (get_setting('pmc_active_' . $item)) {
                if (get_setting('pm_exchange_method') == 'automatic') {
                    $currencies[$item] = get_setting('pmc_auto_rate_' . strtolower($item));
                } else {
                    $currencies[$item] = get_setting('pmc_rate_' . $item);
                }
            }
        }

        if (empty($output)) {
            return $currencies;
        } else {
            return isset($currencies[$output]) ? $currencies[$output] : '';
        }
    }
    /**
     *
     * Exchange rate
     *
     * @version 1.2.1
     * @since 1.0
     * @return void
     */
    public static function exchange_rate($amount, $output = '')
    {
        if (empty($amount)) {
            return false;
        }

        $return = 0;

        $base_currency = get_setting('site_base_currency');
        $decimal = (empty(token('decimal_max')) ? 6 : token('decimal_max'));
        
        $currency_rate = self::active_currency();
        $exchange_rate = [];

        foreach ($currency_rate as $currency => $rate) {
            $currency = strtolower($currency);
            if ($currency == strtolower($base_currency)) {
                $exchange_rate[$currency] = round(($amount * 1), $decimal);
            } else {
                $exchange_rate[$currency] = round(($amount * $rate), $decimal);
            }
        }
        $exchange_rate['base'] = $amount;

        if (empty($output)) {
            $return = $exchange_rate;
        } elseif($output=='except') {
            $ex = $exchange_rate; 
            unset($ex['bch'], $ex['bnb'], $ex['trx'], $ex['xlm'], $ex['xrp'], $ex['usdt'], $ex['try'], $ex['rub'], $ex['cad'], $ex['aud'], $ex['inr'], $ex['ngn'], $ex['brl'], $ex['nzd'], $ex['pln'], $ex['jpy'], $ex['myr'], $ex['mxn'], $ex['php'], $ex['chf'], $ex['thb'], $ex['sgd'], $ex['czk'], $ex['nok'], $ex['zar'], $ex['sek'], $ex['kes'], $ex['nad'], $ex['dkk'], $ex['hkd'], $ex['idr'], $ex['usdc'], $ex['dash'], $ex['waves'], $ex['xmr'], $ex['cdf'], $ex['cfa']);
            $return = $ex;
        } else {
            $output = strtolower($output);
            $return = $exchange_rate[$output];
        }
        return $return;
    }

    /**
     * Update value
     * @param $field
     * @param $value
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public static function updateValue($field, $value)
    {
        $setting = self::where('field', $field)->first();
        if ($setting == null) {
            $setting = new self();
            $setting->field = $field;
        }
        $setting->value = $value;
        if ($setting->save()) {
            return $setting->only(['field', 'value']);
        } else {
            return false;
        }
    }
}
