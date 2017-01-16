<div class="modal fade" tabindex="-1" role="dialog" id="realestate-detail" data-backdrop="static">
    <div class="modal-dialog modal-tab">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pull-left">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#basicPanel" data-toggle="tab"><i class="fa fa-info-circle"></i> {{ trans('common.basicInfo') }}</a></li>
                        <li><a href="#earningPanel" data-toggle="tab"><i class="fa fa-krw"></i> {{ trans('common.earningRate') }}</a></li>
                        <li><a href="#loanPanel" data-toggle="tab"><i class="fa fa-bank"></i> {{ trans('common.loan') }}</a></li>
                    </ul>

                </div>
            </div>
            <div class="modal-body">


                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="basicPanel">
                        <form class="form-horizontal" action="">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.name') }}</label>
                                <div class="col-md-4"><input type="text" class="form-control" name="name"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.address') }}</label>
                                <div class="col-md-6"><input readonly onfocus="daumAddressOpen()" type="text" class="form-control" name="address"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for=""></label>
                                <div class="col-md-4"><div class="checkbox">
                                        <input id="editOwn" class="styled" type="checkbox" name="own">
                                        <label for="editOwn">
                                            {{ trans('common.own') }}
                                        </label>
                                    </div></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for=""></label>
                                <div class="col-md-4">
                                    <div class="btn btn-success btn-basic-edit">{{ trans('common.save') }}</div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="earningPanel">
                        <form class="form-horizontal" action="">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.price') }}</label>
                                <div class="col-md-4"><input name="price" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.deposit') }}</label>
                                <div class="col-md-4"><input name="deposit" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.monthlyFee') }}</label>
                                <div class="col-md-4"><input name="monthly_fee" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.investment') }}</label>
                                <div class="col-md-4"><input name="investment" type="text" class="form-control" readonly></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.interestAmount') }}</label>
                                <div class="col-md-4"><input name="interest_amount"  type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.realErning') }}</label>
                                <div class="col-md-4"><input name="real_earning" type="text" class="form-control" readonly></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for=""></label>
                                <div class="col-md-4">
                                    <div class="btn btn-success btn-earning-edit">{{ trans('common.save') }}</div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="loanPanel">
                        <form class="form-horizontal" action="">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.loanAmount') }}</label>
                                <div class="col-md-4"><input name="amount" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.interestRate') }}</label>
                                <div class="col-md-4"><input name="interest_rate" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.repayCommission') }}</label>
                                <div class="col-md-4"><input name="repay_commission" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.unredeemPeriod') }}</label>
                                <div class="col-md-4"><input name="unredeem_period" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.repayPeriod') }}</label>
                                <div class="col-md-4"><input name="repay_period" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.repayMethod') }}</label>
                                <div class="col-md-4">
                                    <select name="repay_method_id" id="" class="form-control">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.bank') }}</label>
                                <div class="col-md-4"><input name="bank" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.accountNo') }}</label>
                                <div class="col-md-4"><input name="account_no" type="text" class="form-control" readonly></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="">{{ trans('common.optionDeal') }}</label>
                                <div class="col-md-4"><input name="options" type="text" class="form-control"></div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-2" for=""></label>
                                <div class="col-md-4">
                                    <div class="btn btn-success btn-loan-edit">{{ trans('common.save') }}</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close">{{ trans('common.close') }}</button>
            </div>
        </div>
    </div>
</div>