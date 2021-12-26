<div class="modal fade" id="modalreturn">
    <div class="modal-dialog row justify-content-center">
      <div class="modal-content col-12 col-sm-8 col-md-6 col-lg-5 col-xl-3">
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <form>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label>返品理由</label>
                      <input type="text" class="form-control case-return-type">
                      <span class="error error-case-return-type"></span>
                    </div>
                  </div>
                </div>
  
                <div class="text-right">
                  <a href="#">
                    <button type="button" class="btn btn-info btn-round buttonNext btn-return-case">
                      保存
                    </button>
                  </a>
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
<!-- End modalreturn -->

<!-- Form Upload -->
<div class="modal fade" id="modaladdcase" style="top: -25px;">
    <div class="modal-dialog row justify-content-center" style="max-width: 100% !important;">
        <div class="modal-popup modal-content col-12 col-sm-8 col-md-6 col-lg-5 col-xl-3">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="add-temp-cases-form">                            
                            <input type="hidden" id="mst-scrapper-id" name="mst_scrapper_id" value="">
                            <div class="row">  
                                <div class="form-group">
                                    <input data-label="ケース写真" type="hidden" id="case-picture-3" name="case_picture_3" value="">
                                </div>
                                <br>
                                <div>
                                    <div id="screenshotsContainer" class="noneContent2">
                                        <canvas id="canvas-popup" class="is-hidden style="position: absolute"></canvas>
                                        <img id="screenshot" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" id="submit-popup" class="btn btn-info btn-round buttonNext" aria-label="Close">
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
<!-- End modaladdcase -->

<div id="camera-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="camera-screen">
        <div id="modal-content-camera" class="modal-content">
            <div class="modal-header">
                <button type="button" id="close-popup" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="text-align: center;">
                        <video autoplay id="video-popup" style="width: 100%; height: 100%;"></video>
                        <div class="btn-camera-action">                                    
                            <button type="button" class="btn btn-danger btn-round btn-mini" id="btnScreenshot">
                                <i class="fa fa-camera" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-round btn-mini" id="btnChangeCamera">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                    <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Form Upload -->