@extends('layouts.user')
@section('title', $lang['kyc_details'])

@section('content')
@include('layouts.messages')
<div class="content-area card">
        <div class="card-innr">
            <div class="card-head d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 kyc_verifiation">{{$lang['kyc_application_documents']}}</h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ URL::previous() }}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em><span class="span">{{$lang['back']}}</span></a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{ URL::previous() }}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex flex-wrap align-items-center justify-content-between">
                    <div class="fake-class">
                        <span class="data-details-title submitted_by">{{$lang['submitted_by']}}</span>
                        <span class="data-details-info">{{ $kyc->firstName.' '.$kyc->lastName }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title submitted_on">{{$lang['submitted_on']}}</span>
                        <span class="data-details-info">{{ _date($kyc->created_at) }}</span>
                    </div>
                    @if($kyc->reviewedBy != 0)
                    <div class="fake-class">
                        <span class="data-details-title checked_by">{{$lang['checked_by']}}</span>
                        <span class="data-details-info">{{ $kyc->checker_info->name }}</span>
                    </div>
                    @else
                    <div class="fake-class">
                        <span class="data-details-title checked_on">{{$lang['checked_on']}}</span>
                        <span class="data-details-info">Not reviewed yet</span>
                    </div>
                    @endif
                    @if($kyc->reviewedAt != NULL)
                    <div class="fake-class">
                        <span class="data-details-title checked_on">{{$lang['checked_on']}}</span>
                        <span class="data-details-info">{{ _date($kyc->updated_at) }}</span>
                    </div>
                    @endif
                    <div class="fake-class">
                        <span class="badge badge-md badge-block badge-{{ __status($kyc->status,'status')}} ucap">{{ __status($kyc->status,'text') }}</span>
                    </div>
                    @if($kyc->notes !== NULL)
                    <div class="gaps-2x w-100 d-none d-md-block"></div>
                    <div class="w-100">
                        <span class="data-details-title admin_note">{{$lang['admin_note']}}</span>
                        <span class="data-details-info">{!! $kyc->notes !!}</span>
                    </div>
                    @endif
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title personal_information">{{$lang['personal_information']}}</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head fist_name">{{$lang['first_name']}}</div>
                        <div class="data-details-des">{{ $kyc->firstName }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head last_name">{{$lang['last_name']}}</div>
                        <div class="data-details-des">{{ $kyc->lastName }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head email_address">{{$lang['email_address']}}</div>
                        <div class="data-details-des">{{ $kyc->email }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head phone_number">{{$lang['phone_number']}}r</div>
                        <div class="data-details-des">{{ $kyc->phone }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head date_of_birth">{{$lang['date_of_birth']}}</div>
                        <div class="data-details-des">{{ _date($kyc->dob, get_setting('site_date_format')) }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head kyc_your_address">{{$lang['full_address']}}</div>
                        <div class="data-details-des">{{ $kyc->address1 }}, {{ $kyc->address2 }}, {{ $kyc->city }}, {{ $kyc->state }} {{ $kyc->zip }}.</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head country">{{$lang['country_of_residence']}}</div>
                        <div class="data-details-des">{{ $kyc->country }}</div>
                    </li>{{-- li --}}
<!--
                     <li>
                        <div class="data-details-head">Wallet Type</div>
                        <div class="data-details-des">{{ $kyc->walletName }}</div>
                    </li>{{-- li --}}
-->
<!--
                     <li>
                        <div class="data-details-head">Wallet Address</div>
                        <div class="data-details-des">{{ $kyc->walletAddress }}</div>
                    </li>{{-- li --}}
-->
                    <li>
                        <div class="data-details-head kyc_telegram_username">{{$lang['telegram_username']}}</div>
                        <div class="data-details-des"><span{{ '@'.preg_replace('/@/', '', $kyc->telegram, 1) }} </span><a href="https://t.me/{{preg_replace('/@/', '', $kyc->telegram, 1)}}" target="_blank"><em class="far fa-paper-plane"></em></a></div>
                    </li>{{-- li --}}
                </ul>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title upload_documents">{{$lang['uploaded_documents']}}</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">
                            @if($kyc->documentType == 'nidcard')
                            {{$lang['national_id_card']}}
                            @elseif($kyc->documentType == 'passport')
                            {{$lang['passport']}}
                            @elseif($kyc->documentType == 'license')
                            {{$lang['driving_license']}}
                            @else
                            {{$lang['documents']}}
                            @endif
                        </div>
                        @if($kyc->document != NULL)
                        <ul class="data-details-docs">
                            @if($kyc->document != NULL)
                            <li>
                                <span class="data-details-docs-title front_side">{{ $kyc->documentType == 'nidcard' ? $lang['front_side'] : $lang['document'] }}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" target="_blank" ><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif
                            @if($kyc->document2 != NULL)
                            <li>
                                <span class="data-details-docs-title back_side">{{ $kyc->documentType == 'nidcard' ? $lang['back_side'] : $lang['proof'] }}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document2), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif

                            @if($kyc->document3 != NULL)
                            <li>
                                <span class="data-details-docs-title proof">{{$lang['proof']}}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document3), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif
                        </ul>
                        @else 
                        No document uploaded.
                        @endif
                    </li>{{-- li --}}
                </ul>
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection