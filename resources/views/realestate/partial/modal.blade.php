@section('modal')
    <div class="modal fade" tabindex="-1" role="dialog" id="realestate-detail" data-backdrop="static">
        <input type="hidden" name="id">
        <div class="modal-dialog modal-tab">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="pull-left">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#basicPanel" data-toggle="tab"><i class="fa fa-info-circle"></i> {{ trans('common.basicInfo') }}</a></li>
                            <li><a href="#earningPanel" data-toggle="tab"><i class="fa fa-krw"></i> {{ trans('common.earningRate') }}</a></li>
                            <li><a href="#loanPanel" data-toggle="tab"><i class="fa fa-bank"></i> {{ trans('common.loan') }}</a></li>
                            <li><a href="#tenantPanel" data-toggle="tab"><i class="fa fa-user"></i> {{ trans('common.tenant') }}</a></li>
                        </ul>

                    </div>
                </div>
                <div class="modal-body">


                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="basicPanel">
                            <form class="form-horizontal" method="post" onsubmit="return Controller.basicInfoEdit();">
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.name') }}</label>
                                    <div class="col-md-4"><input type="text" class="form-control" name="name" maxlength="50" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.address') }}</label>
                                    <div class="col-md-6"><input readonly onfocus="daumAddressOpen()" type="text" class="form-control" name="address" maxlength="255" required></div>
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
                                    <label class="control-label col-md-2" for="">{{ trans('common.floor') }}</label>
                                    <div class="col-md-4"><input type="text" class="form-control" name="floor" maxlength="10"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.built') }}</label>
                                    <div class="col-md-4">
                                        <div class='input-group date' id="datetimepicker">
                                            <input type="text" class="form-control" name="completed_at" maxlength="15">
                                            <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.exclusiveSize') }}</label>
                                    <div class="col-md-4"><input type="text" class="form-control" name="exclusive_size" maxlength="10"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.memo') }}</label>
                                    <div class="col-md-4"><textarea rows="3" class="form-control" name="memo" maxlength="500"></textarea></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-2" for=""></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-basic-edit btn-block">{{ trans('common.save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="earningPanel">
                            <form class="form-horizontal" method="post" onsubmit="return Controller.earningInfoEdit();">
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.price') }}</label>
                                    <div class="col-md-4"><input name="price" maxlength="20" type="text" class="form-control" required onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.deposit') }}</label>
                                    <div class="col-md-4"><input name="deposit" maxlength="20" type="text" class="form-control" required onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.monthlyFee') }}</label>
                                    <div class="col-md-4"><input name="monthlyfee" maxlength="10" type="text" class="form-control" onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.investment') }}</label>
                                    <div class="col-md-4"><input name="investment" maxlength="20" type="text" class="form-control" readonly></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.mediation_cost') }}</label>
                                    <div class="col-md-4"><input name="mediation_cost" maxlength="20" type="text" class="form-control" onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.judicial_cost') }}</label>
                                    <div class="col-md-4"><input name="judicial_cost" maxlength="10" type="text" class="form-control" onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.tax') }}</label>
                                    <div class="col-md-4"><input name="tax" type="text" maxlength="20" class="form-control" required onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.etc_cost') }}</label>
                                    <div class="col-md-4"><input name="etc_cost" maxlength="20" type="text" class="form-control" onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.interestAmount') }}</label>
                                    <div class="col-md-4"><input name="interest_amount" maxlength="20" type="text" class="form-control" required onchange="Controller.calcRealEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.realEarning') }}</label>
                                    <div class="col-md-4"><input name="real_earning" maxlength="20" type="text" class="form-control" readonly></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-2" for=""></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-earning-edit btn-block">{{ trans('common.save') }}</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div role="tabpanel" class="tab-pane" id="loanPanel">
                            <form class="form-horizontal" method="post" onsubmit="return Controller.loanInfoEdit();">
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.loanAmount') }}</label>
                                    <div class="col-md-4"><input name="amount" maxlength="20" type="text" class="form-control" required onchange="Controller.calcEarning(event);" onfocus="U.Format.remove(event)" onblur="U.Format.comma(event)"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.interestRate') }}</label>
                                    <div class="col-md-4"><input name="interest_rate" maxlength="5" type="text" class="form-control" required onchange="Controller.calcEarning(event);"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.repayCommission') }}</label>
                                    <div class="col-md-4"><input name="repay_commission" maxlength="5" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.unredeemPeriod') }}</label>
                                    <div class="col-md-4"><input name="unredeem_period" maxlength="10" type="text" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.repayPeriod') }}</label>
                                    <div class="col-md-4"><input name="repay_period" maxlength="10" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.repayMethod') }}</label>
                                    <div class="col-md-4">
                                        <select name="repay_method_id" id="" class="select">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.bank') }}</label>
                                    <div class="col-md-4"><input maxlength="10" name="bank" type="text" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.accountNo') }}</label>
                                    <div class="col-md-4"><input maxlength="20" name="account_no" type="text" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.optionDeal') }}</label>
                                    <div class="col-md-4"><textarea maxlength="200" name="options" class="form-control"></textarea></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-2" for=""></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-loan-edit btn-block">{{ trans('common.save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tenantPanel">
                            <form class="form-horizontal" method="post" onsubmit="return Controller.tenantInfoEdit();">
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.tenantName') }}</label>
                                    <div class="col-md-4"><input name="name" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.tenantTel') }}</label>
                                    <div class="col-md-4"><input name="tel" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.tenantPayday') }}</label>
                                    <div class="col-md-4"><input name="pay_day" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.accountNo') }}</label>
                                    <div class="col-md-4"><input name="pay_account_no" type="text" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="">{{ trans('common.bank') }}</label>
                                    <div class="col-md-4"><input name="pay_account_bank" type="text" class="form-control" required></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-2" for=""></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-loan-edit btn-block">{{ trans('common.save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-sheets">{{ trans('common.googleSheets') }}</button>
                    <a href="" id="excelDownload"><button type="button" class="btn btn-info btn-excel">{{ trans('common.excelDownload') }}</button></a>
                    <button type="button" class="btn btn-default btn-close">{{ trans('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="goodauction-import" data-backdrop="static">
        <div class="modal-dialog">
            <form class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('common.goodauctionImport') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.goodauctionURL') }}</label>
                            <div class="col-md-10"><input name="url" type="text" class="form-control" required></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn btn-info btn-import">{{ trans('common.load') }}</div>
                        <div class="btn btn-default btn-close" data-dismiss="modal">{{ trans('common.close') }}</div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="goodauction-import-preview" data-backdrop="static">
        <div class="modal-dialog">
            <form class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('common.goodauctionImport') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.name') }}</label>
                            <div class="col-md-6"><input name="name" type="text" class="form-control" required></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.address') }}</label>
                            <div class="col-md-6"><input name="address" type="text" class="form-control" required readonly></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.floor') }}</label>
                            <div class="col-md-6"><input name="floor" type="text" class="form-control" required readonly></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.completed_at') }}</label>
                            <div class="col-md-6"><input name="completed_at" type="text" class="form-control" required readonly></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.exclusiveSize') }}</label>
                            <div class="col-md-6"><input name="exclusive_size" type="text" class="form-control" required readonly></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn btn-info btn-save">{{ trans('common.save') }}</div>
                        <div class="btn btn-default btn-close" data-dismiss="modal">{{ trans('common.close') }}</div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="sheets-info-modal" data-backdrop="static">
        <div class="modal-dialog">
            <form class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('common.googleSheets') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="">{{ trans('common.name') }}</label>
                            <div class="col-md-6"><input name="sheet_id" type="text" class="form-control" required></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="btn btn-info btn-sheet-export">{{ trans('common.export') }}</div>
                        <div class="btn btn-default btn-close" data-dismiss="modal">{{ trans('common.close') }}</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop