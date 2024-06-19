@extends('layouts.user')
@section('title', $lang['new_investment'])

@section('content')

<form action="javascript:void(0)" method="post" class="token-purchase">
        
        <div class="content-area card">
            <div class="card-innr">

                <div class="card-head">
                    <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 our_plans">{{ $lang['new'] }}</h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{route('user.home')}}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em><span class="back">{{$lang['back']}}</span></a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{route('user.home')}}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                    </div>    
                </div>
                
                <span class="data-head" style="width: 100%; margin-bottom: 15px;display:flex">
                    <span class="data-col sorting_disabled col-6 text-center col-md-4" rowspan="1" colspan="1">{{$lang['plan']}}</span>
                    <span class="data-col sorting_disabled col-md-4 col-sm-3 risk d-none d-md-inline-block" rowspan="1" colspan="1">{{$lang['risk_level']}}</span>
                    <span class="data-col sorting_disabled col-6 text-center col-md-4 expected_roi" rowspan="1" colspan="1" style="padding-right: 50px;">{{$lang['expected_roi']}}</span>
                </span>

                <div>
                    <div class="kyc-option popup-body-innr">
                    <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_ai d-flex" style="padding-bottom:0">
                        <span class="data-col col-4 text-center col-6 text-center col-md-4">
                            <h5 class="kyc-option-title badge badge-xl badge-danger ai_bull" id="ai_bull_title" >AI Bull</h5>
                        </span>
                        <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="leadaih_bull_risk agressive">{{$lang['agressive']}}</span></span>
                        <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead ai_bull_roi">{{ $lang['new'] }}</span></span>
                    </div>

                    <div class="kyc-option-content toggle-content" style="display: none;">

                        <!--                            <div class="card-head d-flex justify-content-between align-items-center">-->
                        <div class="card-head justify-content-between align-items-center">

                            <div class="row align-items-center">
                                
                                <div class="col-sm-12">
                                    <div class="card border-info mb-0">
                                        <div class="card-body">
                                            <p class="text-justify"> The AI Bull is an innovative plan tailored for individuals aiming to capitalize on the expected volatility surrounding the increasing popularity of TAO, FetchAI, SingularityNET, Render, and Worldcoin. This emerging shift in the cryptocurrency market indicates an expansion in the accessibility and usability of these cryptocurrencies. Historically, such shifts have sparked significant price fluctuations, creating opportunities for astute financial strategists.</p>

                                            <h3>Our Strategy:</h3>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">Employing extensive research and analysis of previous market trends to guide our financial decisions.</li>
                                                <li class="list-group-item">Consistently monitoring the wallets of leading TAO, FetchAI, SingularityNET, Render, and Worldcoin miners and major exchanges.</li>
                                                <li class="list-group-item">Gathering insights into the trading behaviors of key market influencers to better predict market movements.</li>
                                                <li class="list-group-item">Aiming to identify potential market changes at the earliest and make informed financial decisions accordingly.</li>
                                            </ul>

                                            <h3>Why Choose The AI Bull?</h3>
                                            <p class="text-justify">For those keen on exploring the dynamic world of TAO, FetchAI, SingularityNET, Render, and Worldcoin during this anticipated period of increased volatility, the AI Bull presents an excellent opportunity. While this trend offers the potential for significant financial gains, it's not without its risks. Our strategy is designed to reduce these uncertainties by using data-driven analysis and proactive management techniques to maximize possible gains while minimizing potential losses.</p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            
                            <div class="row align-items-center mt-4 pt-4 border-top">
                                <div class="col-sm-12">
                                    <h6 class="kyc-option-subtitle select_amount m-0">{{$lang['select_amount']}}</h6>
                                    <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>10000 {{strtoupper($base_cur)}}</strong>. </p>
                                </div>
                                <div class="col-sm-4">
                                    <div class="gaps-1x"></div>
                                    <label class="text-center slider-text slider-text-general" style="width: 100%;">
                                        <select class="slider bull-chart-number input-bordered token-number" id="slider-ai">
                                            <option value="1" class="3months">3 Months</option>
                                            <option value="2" class="6months">6 Months</option>
                                            <option value="3" class="12months">12 Months</option>
                                        </select>
                                    </label>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="gaps-1x"></div>
                                    <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" type="text" id="token-number-ai-bull" value="" min="10000" max="1000000">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="gaps-1x"></div>
                                    <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-ai-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i>
                                        <div></div>
                                    </a>
                                </div>

                                <div class="token-calc-note note token-note">
                                    <div class="note-box">
                                        <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                                        <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
<!--                                        <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>-->
                                    </div>
                                    <div class="note-text note-text-alert text-danger"></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    
        <div class="content-area card">
            <div class="card-innr">

                <div class="card-head">
                    <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 our_plans">{{ $lang['temporary'] }}</h4>
                    <div class="d-flex align-items-center guttar-20px">
<!--
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{route('user.home')}}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em><span class="back">{{$lang['back']}}</span></a>
                        </div>
-->
                        <div class="flex-col d-sm-none">
                            <a href="{{route('user.home')}}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                    </div>    
                </div>
                
                <span class="data-head" style="width: 100%; margin-bottom: 15px;display:flex">
                    <span class="data-col sorting_disabled col-6 text-center col-md-4" rowspan="1" colspan="1">{{$lang['plan']}}</span>
                    <span class="data-col sorting_disabled col-md-4 col-sm-3 risk d-none d-md-inline-block" rowspan="1" colspan="1">{{$lang['risk_level']}}</span>
                    <span class="data-col sorting_disabled col-6 text-center col-md-4 expected_roi" rowspan="1" colspan="1" style="padding-right: 50px;">{{$lang['expected_roi']}}</span>
                </span>

                <div>
                    
                      <div class="kyc-option popup-body-innr">
                    <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_eth d-flex" style="padding-bottom:0">
                        <span class="data-col col-4 text-center col-6 text-center col-md-4">
                            <h5 class="kyc-option-title badge badge-xl eth_bull" id="eth_bull_title" style="background: #446d95;border-color:#446d95">ETH Bull</h5>
                        </span>
                        <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead eth_bull_risk agressive">{{$lang['moderatly_agressive']}}</span></span>
                        <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead eth_bull_roi">{{ $lang['new'] }}</span></span>
                    </div>

                    <div class="kyc-option-content toggle-content" style="display: none;">

                        <!--                            <div class="card-head d-flex justify-content-between align-items-center">-->
                        <div class="card-head justify-content-between align-items-center">

                            <div class="row align-items-center">
                                <div class="col-sm-12">

                                    <p> The Ethereum Bull is a unique investment product specifically designed for individuals looking to capitalize on the upcoming volatility that will stem from the launch of an Ethereum ETF. This exciting new development in the cryptocurrency market, expected to roll out in the coming months, represents an increase in the availability and accessibility of Ethereum as a tradable asset. Historically, such occurrences have been associated with significant fluctuations in price, creating opportunities for savvy investors.</p>
                                    <p>Our Strategy:</p>
                                    <ul class="list-s2">
                                        <li>Leveraging comprehensive research and analysis of past ETF launches to inform our investment decisions.</li>
                                        <li>Continuously monitoring the wallets of leading Ethereum mining corporations and major exchanges.</li>
                                        <li>Gaining insights into the trading habits of key players in the market to better anticipate shifts in direction.</li>
                                        <li>Aiming to detect potential market drifts early on and make informed investment choices accordingly.</li>
                                    </ul>
                                    <p>Why Choose The Ethereum Bull?</p>
                                    <p>For those interested in diving headfirst into the exciting world of Ethereum trading during this predicted period of increased volatility, the Ethereum Bull offers a perfect opportunity. While the introduction of an ETF presents potential for significant returns, it's not without its risks. Our strategy is focused on mitigating these uncertainties by employing data-driven analysis and proactive management techniques to maximize potential profits while minimizing potential losses.</p>
                                </div>

                            </div>
                            <div class="row align-items-center mt-4 pt-4 border-top">
                                <div class="col-sm-12">
                                    <h6 class="kyc-option-subtitle select_amount m-0">{{$lang['select_amount']}}</h6>
                                    <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                </div>
                                <div class="col-sm-6">
                                    <div class="gaps-1x"></div>
                                    <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" type="text" id="token-number-eth-bull" value="" min="500" max="1000000">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="gaps-1x"></div>
                                    <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-eth-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i>
                                        <div></div>
                                    </a>
                                </div>

                                <div class="token-calc-note note token-note">
                                    <div class="note-box">
                                        <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                                        <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                                        <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                                    </div>
                                    <div class="note-text note-text-alert text-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_btc d-flex" style="padding-bottom:0">
                            <span class="data-col col-4 text-center col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl btc_bull" id="btc_bull_title" style="background: #ffa500;border-color:#ffa500">BTC Bull</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead btc_bull_risk agressive">{{$lang['moderate']}}</span></span>
                            <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead btc_bull_roi">{{ $lang['new'] }}</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            
<!--                            <div class="card-head d-flex justify-content-between align-items-center">-->
                            <div class="card-head justify-content-between align-items-center">
                            
                            <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        
                                        <p>
                                            The BTC Halfening Bull is an investment product crafted for those aiming to make the most of the upcoming <a href="https://www.investopedia.com/bitcoin-halving-4843769">BTC halfening</a> event. This significant event, forecasted to take place in April next year, denotes a <a href="https://www.investopedia.com/terms/b/block-reward.asp#:~:text=Bitcoin%20block%20rewards%20are%20new,all%20the%20other%20competing%20miners.">decrease in block rewards</a> for miners. Such events have historically propelled the Bitcoin market into heightened volatility.
                                        </p>

                                        <h3>Our Strategy</h3>
                                        <ul>
                                            <li>Based on in-depth research and analysis of past BTC halvening occurrences.</li>
                                            <li>Constant monitoring of wallets of leading mining corporations and exchanges.</li>
                                            <li>Insight into the purchasing and selling habits of key players to anticipate market directions.</li>
                                            <li>Objective to detect potential market drifts and make enlightened investment choices.</li>
                                        </ul>

                                        <h3>Why Choose The BTC Halfening Bull?</h3>
                                        <p>
                                            It's a perfect match for investors desiring a slice of the Bitcoin market during this predicted phase of intensified volatility. Although the halvening event offers chances for notable returns, it's not without its risks. Our strategy is geared towards circumventing these uncertainties by employing data-backed analysis and proactive management.    
                                        </p>
                                    </div>
 
                                </div>
                                <div class="row align-items-center mt-4 pt-4 border-top">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount m-0">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" type="text" id="token-number-btc-bull" value="" min="500" max="1000000">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-btc-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                                        <div class="note-box">
                                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                                        </div>
                                        <div class="note-text note-text-alert text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="content-area card">
            <div class="card-innr">

                <div class="card-head">
                    <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 our_plans">{{ $lang['our_plans'] }}</h4>
                    
                    </div>    
                </div>
                
                <span class="data-head" style="width: 100%; margin-bottom: 15px;display:flex">
                    <span class="data-col sorting_disabled col-6 text-center col-md-4" rowspan="1" colspan="1">{{$lang['plan']}}</span>
                    <span class="data-col sorting_disabled col-md-4 col-sm-3 risk d-none d-md-inline-block" rowspan="1" colspan="1">{{$lang['risk_level']}}</span>
                    <span class="data-col sorting_disabled col-6 text-center col-md-4 expected_roi" rowspan="1" colspan="1" style="padding-right: 50px;">{{$lang['expected_roi']}}</span>
                </span>

                <div>
                    
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1" style="padding-bottom:0">
                            <span class="data-col col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-light general_bull mr-2" id="general_bull_title">{{$lang['general_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead general_bull_risk moderatly_conservative">{{$lang['moderatly_conservative']}}</span></span>
                            <span class="data-col col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead general_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                        
                                        <div class="card-head has-aside">
<!--                                <h4 class="card-title card-title-sm general_bull_description">{{$lang['general_bull_description']}}</h4>-->
                                
                            </div>
                            <div class="chart-tokensale chart-tokensale-long">
                                <canvas id="tknSale-general"></canvas>
                            </div>
                                    </div>
                                    
                                </div>
                            
                            <div id="general_bull_gains_text" class="bull_gains_text expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} <span style="color: #253992"></span>
                                <div class="tooltipX tooltip-general" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-general col-10">{{$lang['general_bull_description_long']}}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="text-center slider-text slider-text-general" style="width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-general" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                        
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount m-0">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" type="text" id="token-number-general-bull" value="" min="500" max="1000000">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-general-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                        
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>
                
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_crypto d-flex" style="padding-bottom:0">
                            <span class="data-col col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-warning crypto_bull"  id="crypto_bull_title">{{$lang['crypto_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead crypto_bull_risk agressive">{{$lang['moderatly_agressive']}}</span></span>
                            <span class="data-col col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead crypto_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                    
                                    <div class="card-head has-aside">

<!--
                                        <span class="note-icon">
                                            <em class="fas fa-info-circle"></em>
                                        </span>
-->                                     
                                        
<!--
                                        <div class="tooltip2">
                                            <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="right"></em>
                                        </div>
-->
<!--                                        <span class="tooltiptextX">The Crypto Bull that is made for investors who want to invest in the top 10 cryptocurrencies. The Crypto Bull is our most popular product. Everybody is avare of the huge market that crypto currencies have become. Name like Bitcoin, Ethereum, DogeCoin promise high return to their holders, yet historically people lost a lot in this market because of its high volatility.</span>-->
                                    </div>

                                    <div class="chart-tokensale chart-tokensale-long">
                                        <canvas id="tknSale-crypto"></canvas>
                                    </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="crypto_bull_gains_text" class="bull_gains_text general_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} 
                                <span style="color: #253992"></span>
                                <div class="tooltipX tooltip-crypto" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-crypto col-10">{{$lang['crypto_bull_description_long']}}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="text-center slider-text slider-text-crypto" style="width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-crypto" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-crypto-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-crypto-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>
                    
                    
                    <div class="kyc-option popup-body-innr" style="display:none">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_crypto2 d-flex" style="padding-bottom:0">
                            <span class="data-col col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-warning crypto2_bull"  id="crypto2_bull_title" style="background: #ff8100; border-color: #ff8100;">{{$lang['crypto_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead crypto2_bull_risk agressive">{{$lang['agressive']}}</span></span>
                            <span class="data-col col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead crypto2_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                    
                                    <div class="card-head has-aside">

<!--
                                        <span class="note-icon">
                                            <em class="fas fa-info-circle"></em>
                                        </span>
-->                                     
                                        
<!--
                                        <div class="tooltip2">
                                            <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="right"></em>
                                        </div>
-->
<!--                                        <span class="tooltiptextX">The Crypto Bull that is made for investors who want to invest in the top 10 cryptocurrencies. The Crypto Bull is our most popular product. Everybody is avare of the huge market that crypto currencies have become. Name like Bitcoin, Ethereum, DogeCoin promise high return to their holders, yet historically people lost a lot in this market because of its high volatility.</span>-->
                                    </div>

                                    <div class="chart-tokensale chart-tokensale-long">
                                        <canvas id="tknSale-crypto2"></canvas>
                                    </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="crypto2_bull_gains_text" class="bull_gains_text general_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} 
                                <span style="color: #253992"></span>
<!--
                                <div class="tooltipX tooltip-crypto" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-crypto col-10">{{$lang['crypto_bull_description_long']}}</p>
                                    </div>
                                </div>
-->
                            </div>
                            
                            <label class="text-center slider-text slider-text-crypto2" style="width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-crypto2" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-crypto2-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction token-payment-btn-crypto2-bull" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>
                    
                    
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_nft d-flex" style="padding-bottom:0">
                            <span class="data-col col-4 text-center col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-lighter nft_bull" id="nft_bull_title">{{$lang['nft_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead nft_bull_risk agressive">{{$lang['moderate']}}</span></span>
                            <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead nft_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                   
                                    <div class="card-head has-aside">
<!--                                <h4 class="card-title card-title-sm nft_bull_description">{{$lang['nft_bull_description']}}</h4>-->
                           
                            </div>
                            <div class="chart-tokensale chart-tokensale-long">
                                <canvas id="tknSale-nft"></canvas>
                            </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="nft_bull_gains_text" class="bull_gains_text nft_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} <span style="color: #253992"></span>
                                <div class="tooltipX tooltip-nft" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-nft col-10">{{$lang['nft_bull_description_long']}}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="text-center slider-text slider-text-nft" style=" width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-nft" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-nft-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction" novalidate="novalidate">Make Transaction&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>

                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_metaverse d-flex" style="padding-bottom:0">
                            <span class="data-col col-4 text-center col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-purple metaverse_bull" id="metaverse_bull_title">{{$lang['metaverse_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead metaverse_bull_risk agressive">{{$lang['moderate']}}</span></span>
                            <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead metaverse_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                   
                                    <div class="card-head has-aside">
<!--                                <h4 class="card-title card-title-sm metaverse_bull_description">{{$lang['metaverse_bull_description']}}</h4>-->
                           
                            </div>
                            <div class="chart-tokensale chart-tokensale-long">
                                <canvas id="tknSale-metaverse"></canvas>
                            </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="metaverse_bull_gains_text" class="bull_gains_text metaverse_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}}  <span style="color: #253992"></span>
<!--
                                <div class="tooltipX tooltip-metaverse" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-metaverse col-10">{{$lang['metaverse_bull_description_long']}}</p>
                                    </div>
                                </div>
-->
                            </div>
                            
                            <label class="text-center slider-text slider-text-metaverse" style=" width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-metaverse" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-metaverse-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <a data-type="offline" href="#payment-modal" id="offline_payment" class="btn btn-primary payment-btn token-payment-btn offline_payment active-btn disabled make_transaction" novalidate="novalidate">{{$lang['make_transaction']}}&nbsp;<i class="ti ti-wallet"></i><div></div></a>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div> 
                    
                </div>

            </div>
        </div>
        <div class="content-area card">
            <div class="card-innr">

                <div class="card-head">
                    <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 our_plans">{{ $lang['currently_unavailable'] }}</h4>
                    </div>    
                </div>
                
                <span class="data-head" style="width: 100%; margin-bottom: 15px;display:flex">
                    <span class="data-col sorting_disabled col-6 text-center col-md-4" rowspan="1" colspan="1">{{$lang['plan']}}</span>
                    <span class="data-col sorting_disabled col-md-4 col-sm-3 risk d-none d-md-inline-block" rowspan="1" colspan="1">{{$lang['risk_level']}}</span>
                    <span class="data-col sorting_disabled col-6 text-center col-md-4 expected_roi" rowspan="1" colspan="1" style="padding-right: 50px;">{{$lang['expected_roi']}}</span>
                </span>

                <div>
                    
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_ecological d-flex" style="padding-bottom:0">
                            <span class="data-col col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-success ecological_bull" id="ecological_bull_title">{{$lang['ecological_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead ecological_bull_risk moderate">{{$lang['moderate']}}</span></span>
                            <span class="data-col col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead ecological_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    <div class="col-sm-12">
                                    <div class="card-head has-aside">
<!--                                <h4 class="card-title card-title-sm ecological_bull_description">{{$lang['ecological_bull_description']}}</h4>-->
                               
                            </div>
                            <div class="chart-tokensale chart-tokensale-long">
                                <canvas id="tknSale-ecological"></canvas>
                            </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="ecological_bull_gains_text" class="bull_gains_text ecological_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} <span style="color: #253992"></span>
                                <div class="tooltipX tooltip-ecological" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-ecological col-10">{{$lang['ecological_bull_description_long']}}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="text-center slider-text slider-text-ecological" style="width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-ecological" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-ecological-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 d-none">
                                        <div class="gaps-1x"></div>
                                        
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>

                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_commodities d-flex" style="padding-bottom:0">
                            <span class="data-col col-4 text-center col-6 text-center col-md-4"><h5 class="kyc-option-title badge badge-xl badge-dark commodities_bull" id="commodities_bull_title">{{$lang['commodities_bull']}}</h5></span>
                            <span class="data-col col-4 text-center d-none d-md-inline-block"><span class="lead commodities_bull_risk agressive">{{$lang['conservative']}}</span></span>
                            <span class="data-col col-4 text-center col-6 text-center col-md-4" style="padding-right: 25px;"><span class="lead commodities_bull_roi">0%</span></span>
                        </div>
                        
                        <div class="kyc-option-content toggle-content" style="display: none;">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    
                                    
                                    <div class="col-sm-12">
                                   
                                    <div class="card-head has-aside">
<!--                                <h4 class="card-title card-title-sm commodities_bull_description">{{$lang['commodities_bull_description']}}</h4>-->
                           
                            </div>
                            <div class="chart-tokensale chart-tokensale-long">
                                <canvas id="tknSale-commodities"></canvas>
                            </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="commodities_bull_gains_text" class="bull_gains_text commodities_bull_difference expected_profit" style="border-bottom: 1px solid #e6effb;padding-bottom: 5px;color: #758698;font-weight: 600;">{{$lang['expected_profit']}} <span style="color: #253992"></span>
<!--
                                <div class="tooltipX tooltip-commodities" style="float: right;">
                                    <em class="fas fa-info-circle" style="cursor: pointer;" data-placement="right"></em>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <p class="tooltiptextX tooltiptext-commodities col-10">{{$lang['commodities_bull_description']}}</p>
                                    </div>
                                </div>
-->
                            </div>
                            
                            <label class="text-center slider-text slider-text-commodities" style=" width: 100%;"><ul class="d-flex justify-content-between"><li class="3months">{{$lang['3_months']}}</li><li class="6months">{{$lang['6_months']}}</li><li class="12months">{{$lang['12_months']}}</li></ul></label>
                            <input type="range" min="1" max="3" value="1" class="slider" id="slider-commodities" style="width: 100%;margin-bottom: 20px;">
                            
                            <div class="card-head d-flex justify-content-between align-items-center">
                            
                            <!--<div class="input-item">-->
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h6 class="kyc-option-subtitle select_amount">{{$lang['select_amount']}}</h6>
                                        <p class="small minimum_amount">{{$lang['the_minimum_amount_for_the_investment_is']}} <strong>5000 {{strtoupper($base_cur)}}</strong>. </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="gaps-1x"></div>
                                        <div class="token-pay-amount payment-get"><input class="input-bordered input-with-hint token-number bull-chart-number" value="" type="text" id="token-number-commodities-bull" min="500" max="849993">
                                        <div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">{{$base_cur}}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 d-none">
                                        <div class="gaps-1x"></div>
                                    </div>
                                    
                                    <div class="token-calc-note note token-note">
                        <div class="note-box">
                            <span class="note-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="note-text text-light rb_commission">{{$lang['robotbulls_takes_10p_only']}}</span>
                            <span class="note-text text-light">{{$lang['robotbulls_stoploss']}}</span>
                        </div>
                        <div class="note-text note-text-alert text-danger"></div>
                    </div>
                                    
                                </div>
                            <!--</div>-->
                            
                            <!--<ul class="btn-grp btn-grp-block guttar-20px">-->

                            <!--<li>-->
                                
                            <!--</li>-->
                            
                            </div>

                            <!--</ul>-->
                            
                            
                            
                            
                            <div class="gaps-2x"></div>
                            
                        </div>
                    
                    </div>
                      
                </div>

            </div>
        </div>
        <input type="hidden" id="data_amount" value="0">
        <input type="hidden" id="data_currency" value="{{ $bc }}">

</form>

@push('sidebar')
<div class="aside sidebar-right col-lg-4">
    <div class="card">
        {!! UserPanel::how_to_buy_crypto($lang, ['class' => 'mb-0']) !!}
    </div>
</div>
@endpush

@endsection
    
@section('modals')
<div class="modal fade modal-payment" id="payment-modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content"></div>
    </div>
</div>
@endsection


@push('header')
<script>
    function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
    }
    var access_url = "{{ route('user.ajax.investment.access') }}";
    var minimum_token = {{ $min_token }}, maximum_token ={{ $max_token }}, token_price = '', token_symbol = "{{ $symbol }}", base_bonus = 0, amount_bonus = {100 : 0}, decimals = {"min":6, "max":15 }, base_currency = "{{ base_currency() }}", base_method = "{{ $method }}", plan = getCookie('plan'), duration = getCookie('duration');
    var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
@endpush


@push('footer') 
<script>
var data_bulls = {
    //General Bull
    general_dates3m: "{{implode(',',$dates3msymbol_gspc)}}",
    general_dates1y: "{{implode(',',$dates1ysymbol_gspc)}}",
    general_data3m_s: "{{implode(',',$close3msymbol_gspc)}}",
    general_data1y_s: "{{implode(',',$close1ysymbol_gspc)}}",
    general_data3m_b: "{{implode(',',$close3mgeneral_bull)}}",
    general_data1y_b: "{{implode(',',$close1ygeneral_bull)}}",
    
    //Crypto Bull
    crypto_dates3m: "{{implode(',',$dates3msymbol_btc)}}",
    crypto_dates1y: "{{implode(',',$dates1ysymbol_btc)}}",
    crypto_data3m_s: "{{implode(',',$close3msymbol_btc)}}",
    crypto_data1y_s: "{{implode(',',$close1ysymbol_btc)}}",
    crypto_data3m_b: "{{implode(',',$close3mcrypto_bull)}}",
    crypto_data1y_b: "{{implode(',',$close1ycrypto_bull)}}",
    
    //NFT Bull
    nft_dates3m: "{{implode(',',$dates3msymbol_eth)}}",
    nft_dates1y: "{{implode(',',$dates1ysymbol_eth)}}",
    nft_data3m_s: "{{implode(',',$close3msymbol_eth)}}",
    nft_data1y_s: "{{implode(',',$close1ysymbol_eth)}}",
    nft_data3m_b: "{{implode(',',$close3mnft_bull)}}",
    nft_data1y_b: "{{implode(',',$close1ynft_bull)}}",
    
    //Metamask Bull
    metaverse_dates3m: "{{implode(',',$dates3msymbol_metv)}}",
    metaverse_dates1y: "{{implode(',',$dates1ysymbol_metv)}}",
    metaverse_data3m_s: "{{implode(',',$close3msymbol_metv)}}",
    metaverse_data1y_s: "{{implode(',',$close1ysymbol_metv)}}",
    metaverse_data3m_b: "{{implode(',',$close3mmetaverse_bull)}}",
    metaverse_data1y_b: "{{implode(',',$close1ymetaverse_bull)}}",
    
    //Ecological Bull
    ecological_dates3m: "{{implode(',',$dates3msymbol_bgrn)}}",
    ecological_dates1y: "{{implode(',',$dates1ysymbol_bgrn)}}",
    ecological_data3m_s: "{{implode(',',$close3msymbol_bgrn)}}",
    ecological_data1y_s: "{{implode(',',$close1ysymbol_bgrn)}}",
    ecological_data3m_b: "{{implode(',',$close3mecological_bull)}}",
    ecological_data1y_b: "{{implode(',',$close1yecological_bull)}}",
    
    //Commodities Bull
    commodities_dates3m: "{{implode(',',$dates3msymbol_bcx)}}",
    commodities_dates1y: "{{implode(',',$dates1ysymbol_bcx)}}",
    commodities_data3m_s: "{{implode(',',$close3msymbol_bcx)}}",
    commodities_data1y_s: "{{implode(',',$close1ysymbol_bcx)}}",
    commodities_data3m_b: "{{implode(',',$close3mcommodities_bull)}}",
    commodities_data1y_b: "{{implode(',',$close1ycommodities_bull)}}"
};
</script>
<script src="{{ asset('assets/js/investment.js') }}"></script>
<script src="{{ asset('assets/js/investment_charts.js') }}"></script>
@endpush



