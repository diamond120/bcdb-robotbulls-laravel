<div class="modal fade" id="user-activities" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">Refferal user lists
                    <em class="ti ti-angle-right"></em> <small class="user-id">{{ set_id($user->id) }}</small>
                </h3>
                <ul class="data-details-alt">
                    {{-- @dd($refered) --}}
                    @forelse($refered as $refer)
                    <li class="text-dark row no-gutters justify-content-between">
                        <div class="col-12 col-lg order-lg-last">
                            <span class="fs-12 text-light data-details-date">{{'joined'}} . {{ _date($refer->created_at) }}</span>
                        </div>
                        <div class="col-md col-6">
                            <strong class="text-dark">{{ set_id($refer->id).' - '.$refer->name }}</strong>
                            <span class="small">{{ $refer->email }}</span>
                        </div>
                        @if (auth()->user()->id == '1')
                        <div class="col-md col-6 text-right text-md-left"><a target="_blank" href="{{ route('admin.users.view', [$refer->id, 'details'] ) }}">{{'view_user'}}</a></div>
                        @endif
                        <div class="col-md col-sm-6">
                            @if(isset($refer->refer_to) && $refer->refer_to > 0) 
                            <a href="javascript:void(0)" data-uid="{{ $refer->id }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-search-plus"></em> {{ $refer->refer_to . 'contributors' }}</a>
                            @else 
                            ~
                            @endif
                        </div>
                    </li>
                    @empty
                    <li><div class="col-md col-sm-6"><strong class="text-dark">{{'no_one_joined_yet'}}</strong></div></li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>