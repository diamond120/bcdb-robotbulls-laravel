<div class="modal fade" id="messages-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title two_fator_verifiation">{{ $lang['contact_us'] }}</h3>
                    <div class="msg-box"></div>
                        
                    @php
                        use Illuminate\Support\Facades\Crypt;
                        use Illuminate\Contracts\Encryption\DecryptException;
                        use Carbon\Carbon;
                        
                        $user = Auth::user();
                        $messages = DB::table('client_messages')
                                        ->where('user', $user->id)
                                        ->get(); // Execute the query and get the results

                        // Filter messages to check if there's at least one with the channel 'support'
                        $hasSupportMessages = $messages->contains(function ($message) {
                            try {
                                $channelDecrypted = Crypt::decryptString($message->channel);
                                return $channelDecrypted === "support";
                            } catch (DecryptException $e) {
                                return false;
                            }
                        });
                    @endphp

                    @if($hasSupportMessages)
                        <label class="clear input-item-label mt-2">{{ $lang['past_messages'] }}</label>
                        <ul class="data-details-alt" id="messagesList">
                            @foreach($messages as $message)
                                @php
                                    try {
                                        $fromDecrypted = Crypt::decryptString($message->from);
                                        $messageDecrypted = Crypt::decryptString($message->message);
                                        $createdAtFormatted = Carbon::parse($message->created_at)->format('d M Y');
                                        $channelDecrypted = Crypt::decryptString($message->channel);
                                    } catch (DecryptException $e) {
                                        $fromDecrypted = '[encrypted]';
                                        $messageDecrypted = '[encrypted]';
                                        $createdAtFormatted = '[encrypted]';
                                        $channelDecrypted = '[encrypted]';
                                    }
                                @endphp
                                @if($channelDecrypted == "support")
                                    <li class="text-dark row no-gutters justify-content-between">
                                        <div class="col-md col-sm-3"><center><strong class="text-dark">{{$fromDecrypted}}</strong></center></div>
                                        <div class="col-md col-sm-3"><center><span class="text-dark">{{$messageDecrypted}}</span></center></div>
                                        <div class="col-md col-sm-3"><center><span class="text-dark">{{$createdAtFormatted}}</span></center></div>
                                        <div class="col-md col-sm-3"><center><span class="text-dark">{{$channelDecrypted}}</span></center></div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif


                
                <form class="validate-modern" action="{{ route('user.ajax.support.new_message') }}" method="POST" autocomplete="off">
                    @csrf                    
                    <div class="input-item input-with-label pb-0 mt-3">
                        <label class="clear input-item-label">{{ $lang['your_message'] }}</label>
                        <div class="input-wrap">
                            <textarea required="required" id="sms_textarea" name="message" class="input-bordered cls input-textarea input-textarea-sm" type="text" placeholder="{{ $lang['write_something'] }}" style="resize:vertical; height: 200px;"></textarea>
                        </div>
                    </div>
                       
                    <div class="pdb-1-5x mt-3">
                        <button type="submit" class="btn btn-primary update_profile">{{ $lang['send_message'] }}</button>
                    </div>
                </form>
                
                
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>