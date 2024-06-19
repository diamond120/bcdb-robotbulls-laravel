@extends('layouts.admin')
@section('title', ucfirst($is_page).' Wallets')
@section('content')

@php
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
@endphp

<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Wallets</h4>
                </div>

                @if (auth()->user()->id == '1')
                    <div class="page-nav-wrap">
                        <div class="page-nav-bar justify-content-between bg-lighter">
                            <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                                <form action="{{ route('admin.wallets') }}" method="GET" autocomplete="off">
                                    <div class="input-wrap">
                                        <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                        <input type="search" class="input-solid input-transparent" placeholder="Search with wallet address/user ID" value="{{ request()->get('s', '') }}" name="s">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                
                <table class="data-table user-list">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col data-col-wd-md filter-data dt-user">User</th>
                            <th class="data-col dt-token">ETH</th>
                            <th class="data-col dt-token">USDT</th>
                            <th class="data-col dt-token">USDC</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wallets as $wallet)
                        
                        @php
                            $user = User::find($wallet->user_id);
                        @endphp
                        
                        <tr class="data-item">
                            <td class="data-col data-col-wd-md dt-user">
                                <center>
                                    <div class="d-flex align-items-center">
                                        <div class="fake-class">
                                            <span class="lead user-name">
                                                <span>{{ $wallet->custom_wallet_name}} <span class="small"><a href="{{ route('admin.users.view', [$user->id, 'details'] ) }}" target="_blank">{{$user->id }}</a></span></span>
                                            </span>
                                            <span class="small">({{ round(floatval($user->tokenBalance),3) }} || {{ round(floatval($user->equity),3) }})</span>
                                            <span class="sub user-id">{{ Crypt::decryptString($wallet->wallet_address) }}</span>
                                        </div>
                                    </div>
                                </center>
                            </td>
                            <td class="data-col dt-token">
                                <center>
<!--                                    <span class="lead lead-btoken">{{ round($wallet->balanceETH, 3) }}</span>-->
                                </center>
                            </td>
                            <td class="data-col dt-token">
                                <center>
<!--                                    <span class="lead lead-btoken">{{ round($wallet->balanceUSDT, 3) }}</span>-->
                                </center>
                            </td>
                            <td class="data-col dt-token">
                                <center>
<!--                                    <span class="lead lead-btoken">{{ round($wallet->balanceUSDC, 3) }}</span>-->
                                </center>
                            </td>
                            <td class="data-col text-right">
                                @if($user->whitelisting_comptete == 1 || $wallet->user_id == 1)
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul id="more-menu" class="dropdown-list">
                                            @if($user->whitelisting_comptete == 1)
                                                <li><a href="{{ route('admin.resetWhitelisting', $user->id) }}"><em class="fa fa-undo"></em> Reset Whitelisting</a></li>
                                            @endif
                                            @if($wallet->user_id == 1)
                                                <li><a href="{{ route('admin.resetWhitelisting', $user->id) }}"><em class="fa fa-undo"></em> Withdraw</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </td>

                        </tr>
                        {{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection

