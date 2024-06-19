<div class="modal fade" id="user-activities" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">Reinvestment <em class="ti ti-angle-right"></em> <small class="tnx-id">{{ set_id($user->name) }}</small></h3>
                <form action="{{ route('admin.ajax.transactions.reinvest') }}" method="POST" class="validate-modern" id="add_token" autocomplete="off">
                    <div class="row">
                        <input class="input-bordered d-none" required="" type="text" name="trnx_id" value="{{ $reinvestment->id }}">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan</label>
                                <div class="input-wrap">
                                    <select name="plan" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Reinvest</button>
                    <div class="gaps-3x"></div>
                    <div class="note note-plane note-light">
                        <em class="fas fa-info-circle"></em>
                        <p>If checked <strong>'Bonus Adjusted'</strong>, it will applied bonus based on selected stage (only for Purchase type).</p>
                    </div>
                    

                    
                </form>
            </div>
        </div>
    </div>
</div>