<!-- modalreceivedate -->
<div class="modal fade" id="modalreceivedate">
    <div class="modal-dialog row justify-content-center">
        <div class="modal-content col-12 col-sm-8 col-md-6 col-lg-5 col-xl-3">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="planCaseUpdateForm">
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" id="planCase_id" name="planCase_id" value="">

                                    <div class="form-group">
                                        <label>受入予定日<span class="red">*</span></label>
                                        <input type="text" data-label="受入予定日" class="form-control datepicker" id="receive_plan_date" name="receive_plan_date">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>メモ</label>
                                        <input type="text" data-label="メモ" id="receive_plan_memo" class="form-control" name="receive_plan_memo">
                                    </div>
                                </div>
                            </div>
                            ​
                            <div class="text-right">
                                <button type="submit" value="submit" id="submit"  class="btn btn-info btn-round buttonNext" >
                                    登録
                                </button>
                                <button type="button" class="btn btn-warning btn-round" data-dismiss="modal" aria-label="Close">
                                    キャンセル
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End modalreceivedate -->