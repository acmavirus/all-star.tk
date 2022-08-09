<style>
    fieldset > .row > .col-6{
        border: 2px solid #04df;
        margin: 10px 0;
    }
</style>
<?php if(!empty($domain)):
    $domain_fix = str_replace('.','',$domain);
    $CF = "700x100";
    $AF = "1170x100";
    $A = "580x100";
    $B = "120x300";
    $C = "360x100";
    $S1 = "800x100";
    $S2 = "170x550";
    $E = "240x220";
    $F = "700x100";
//    dd($banner);
    ?>
<!--fix size banner-->
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][CF][size]" value="<?php echo $CF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][CF2][size]" value="<?php echo $CF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][CF3][size]" value="<?php echo $CF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][AF1][size]" value="<?php echo $AF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][AF2][size]" value="<?php echo $AF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][AF3][size]" value="<?php echo $AF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][AF4][size]" value="<?php echo $AF?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A1][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A2][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A3][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A4][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A5][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A6][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A7][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A8][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A9][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][A10][size]" value="<?php echo $A?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][B1][size]" value="<?php echo $B?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][B2][size]" value="<?php echo $B?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][B3][size]" value="<?php echo $B?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][B4][size]" value="<?php echo $B?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][C1][size]" value="<?php echo $C?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][C2][size]" value="<?php echo $C?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][S1][size]" value="<?php echo $S1?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][S2][size]" value="<?php echo $S2?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][E1][size]" value="<?php echo $E?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][E2][size]" value="<?php echo $E?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][E3][size]" value="<?php echo $E?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][E4][size]" value="<?php echo $E?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F1][size]" value="<?php echo $F?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F2][size]" value="<?php echo $F?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F3][size]" value="<?php echo $F?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F4][size]" value="<?php echo $F?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F5][size]" value="<?php echo $F?>" />
    <input type="hidden" name="banner[<?php echo $domain_fix ?>][F6][size]" value="<?php echo $F?>" />
<!--end fix size banner-->

<div>

    <fieldset>
        <legend class="bg-primary text-white">Click hiển thị popup lần đầu trong ngày - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Chỉ tác dụng khi click vào site lần đầu trong ngày</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][Popup][expired]" value="<?php echo !empty($banner[$domain_fix]['Popup']['expired']) ? $banner[$domain_fix]['Popup']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][Popup][link]" value="<?php echo !empty($banner[$domain_fix]['Popup']['link']) ? $banner[$domain_fix]['Popup']['link'] : '' ?>" class="form-control m-input">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend class="bg-primary text-white">Banner Preload - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Hiển thị khi vào site lần đầu trong ngày</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][Preload][expired]" value="<?php echo !empty($banner[$domain_fix]['Preload']['expired']) ? $banner[$domain_fix]['Preload']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][Preload][link]" value="<?php echo !empty($banner[$domain_fix]['Preload']['link']) ? $banner[$domain_fix]['Preload']['link'] : '' ?>" class="form-control m-input">
                    </div>

                    <label>Banner</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <div class="input-group-prepend">
                            <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                                <i class="la la-picture-o"></i>
                            </span>
                        </div>
                        <input type="text" name="banner[<?php echo $domain_fix ?>][Preload][content]" value="<?php echo !empty($banner[$domain_fix]['Preload']['content']) ? $banner[$domain_fix]['Preload']['content'] : '' ?>" class="form-control m-input chooseImage">
                    </div>
                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                        <a target="_blank" href="//media.vuive.live/<?php echo !empty($banner[$domain_fix]['Preload']['content']) ? $banner[$domain_fix]['Preload']['content'] : '' ?>">
                            Xem thử
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend class="bg-primary text-white">Button đặt cược - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí Button link đặt cược:</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][Button][expired]" value="<?php echo !empty($banner[$domain_fix]['Button']['expired']) ? $banner[$domain_fix]['Button']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][Button][link]" value="<?php echo !empty($banner[$domain_fix]['Button']['link']) ? $banner[$domain_fix]['Button']['link'] : '' ?>" class="form-control m-input">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend class="bg-primary text-white">Button menu - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí Button menu:</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][Button_menu][expired]" value="<?php echo !empty($banner[$domain_fix]['Button_menu']['expired']) ? $banner[$domain_fix]['Button_menu']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <textarea name="banner[<?php echo $domain_fix ?>][Button_menu][link]" rows="5" class="form-control m-input">
                            <?php echo !empty($banner[$domain_fix]['Button_menu']['link']) ? $banner[$domain_fix]['Button_menu']['link'] : '' ?>
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">CF - <?php echo $domain ?></legend>
        <div class="row">

            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí catfish mobile (<?php echo $CF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][CF][expired]" value="<?php echo !empty($banner[$domain_fix]['CF']['expired']) ? $banner[$domain_fix]['CF']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF][link_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF']['link_mobile']) ? $banner[$domain_fix]['CF']['link_mobile'] : '' ?>" class="form-control m-input">
                    </div>

                    <label>Banner Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <div class="input-group-prepend">
                            <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                                <i class="la la-picture-o"></i>
                            </span>
                        </div>
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF][content_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF']['content_mobile']) ? $banner[$domain_fix]['CF']['content_mobile'] : '' ?>" class="form-control m-input chooseImage">
                    </div>
                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                        <a target="_blank" href="//media.vuive.live/<?php echo !empty($banner[$domain_fix]['CF']['content_mobile']) ? $banner[$domain_fix]['CF']['content_mobile'] : '' ?>">
                            Xem thử
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí catfish mobile 2 (<?php echo $CF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][CF2][expired]" value="<?php echo !empty($banner[$domain_fix]['CF2']['expired']) ? $banner[$domain_fix]['CF2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF2][link_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF2']['link_mobile']) ? $banner[$domain_fix]['CF2']['link_mobile'] : '' ?>" class="form-control m-input">
                    </div>

                    <label>Banner Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <div class="input-group-prepend">
                            <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                                <i class="la la-picture-o"></i>
                            </span>
                        </div>
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF2][content_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF2']['content_mobile']) ? $banner[$domain_fix]['CF2']['content_mobile'] : '' ?>" class="form-control m-input chooseImage">
                    </div>
                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                        <a target="_blank" href="//media.vuive.live/<?php echo !empty($banner[$domain_fix]['CF2']['content_mobile']) ? $banner[$domain_fix]['CF2']['content_mobile'] : '' ?>">
                            Xem thử
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí catfish mobile 3 (<?php echo $CF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][CF3][expired]" value="<?php echo !empty($banner[$domain_fix]['CF3']['expired']) ? $banner[$domain_fix]['CF3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <label>Link Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF3][link_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF3']['link_mobile']) ? $banner[$domain_fix]['CF3']['link_mobile'] : '' ?>" class="form-control m-input">
                    </div>

                    <label>Banner Mobile</label>
                    <div class="input-group m-input-group m-input-group--air">
                        <div class="input-group-prepend">
                            <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                                <i class="la la-picture-o"></i>
                            </span>
                        </div>
                        <input type="text" name="banner[<?php echo $domain_fix ?>][CF3][content_mobile]" value="<?php echo !empty($banner[$domain_fix]['CF3']['content_mobile']) ? $banner[$domain_fix]['CF3']['content_mobile'] : '' ?>" class="form-control m-input chooseImage">
                    </div>
                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                        <a target="_blank" href="//media.vuive.live/<?php echo !empty($banner[$domain_fix]['CF3']['content_mobile']) ? $banner[$domain_fix]['CF3']['content_mobile'] : '' ?>">
                            Xem thử
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí AF1 (<?php echo $AF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][AF1][expired]" value="<?php echo !empty($banner[$domain_fix]['AF1']['expired']) ? $banner[$domain_fix]['AF1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'AF1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí AF2 (<?php echo $AF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][AF2][expired]" value="<?php echo !empty($banner[$domain_fix]['AF2']['expired']) ? $banner[$domain_fix]['AF2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'AF2']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí AF3 (<?php echo $AF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][AF3][expired]" value="<?php echo !empty($banner[$domain_fix]['AF3']['expired']) ? $banner[$domain_fix]['AF3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'AF3']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí AF4 (<?php echo $AF?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][AF4][expired]" value="<?php echo !empty($banner[$domain_fix]['AF4']['expired']) ? $banner[$domain_fix]['AF4']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'AF4']) ?>
            </div>




            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col-3 d-flex align-items-center m--font-bold">Vị trí A1 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A1][expired]" value="<?php echo !empty($banner[$domain_fix]['A1']['expired']) ? $banner[$domain_fix]['A1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A2 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A2][expired]" value="<?php echo !empty($banner[$domain_fix]['A2']['expired']) ? $banner[$domain_fix]['A2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A2']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A3 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A3][expired]" value="<?php echo !empty($banner[$domain_fix]['A3']['expired']) ? $banner[$domain_fix]['A3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A3']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A4 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A4][expired]" value="<?php echo !empty($banner[$domain_fix]['A4']['expired']) ? $banner[$domain_fix]['A4']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A4']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A5 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A5][expired]" value="<?php echo !empty($banner[$domain_fix]['A5']['expired']) ? $banner[$domain_fix]['A5']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A5']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A6 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A6][expired]" value="<?php echo !empty($banner[$domain_fix]['A6']['expired']) ? $banner[$domain_fix]['A3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A6']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A7 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A7][expired]" value="<?php echo !empty($banner[$domain_fix]['A7']['expired']) ? $banner[$domain_fix]['A7']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A7']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A8 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A8][expired]" value="<?php echo !empty($banner[$domain_fix]['A8']['expired']) ? $banner[$domain_fix]['A8']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A8']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A9 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A9][expired]" value="<?php echo !empty($banner[$domain_fix]['A9']['expired']) ? $banner[$domain_fix]['A9']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A9']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí A10 (<?php echo $A?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][A10][expired]" value="<?php echo !empty($banner[$domain_fix]['A10']['expired']) ? $banner[$domain_fix]['A10']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'A10']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">B - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí B1 (<?php echo $B?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][B1][expired]" value="<?php echo !empty($banner[$domain_fix]['B1']['expired']) ? $banner[$domain_fix]['B1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'B1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí B2 (<?php echo $B?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][B2][expired]" value="<?php echo !empty($banner[$domain_fix]['B2']['expired']) ? $banner[$domain_fix]['B2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'B2']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí B3 (<?php echo $B?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][B3][expired]" value="<?php echo !empty($banner[$domain_fix]['B3']['expired']) ? $banner[$domain_fix]['B3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'B3']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí B4 (<?php echo $B?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][B4][expired]" value="<?php echo !empty($banner[$domain_fix]['B4']['expired']) ? $banner[$domain_fix]['B4']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'B4']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">C - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí C1 (<?php echo $C?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][C1][expired]" value="<?php echo !empty($banner[$domain_fix]['C1']['expired']) ? $banner[$domain_fix]['C1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'C1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí C2 (<?php echo $C?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][C2][expired]" value="<?php echo !empty($banner[$domain_fix]['C2']['expired']) ? $banner[$domain_fix]['C2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'C2']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">D - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí D1 (Chạy chữ khi video chạy):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][D1][expired]" value="<?php echo !empty($banner[$domain_fix]['D1']['expired']) ? $banner[$domain_fix]['D1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'D1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí D2 (Hiện lên khi video chạy):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][D2][expired]" value="<?php echo !empty($banner[$domain_fix]['D2']['expired']) ? $banner[$domain_fix]['D2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'D2']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">S - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí S1 (<?php echo $S1?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][S1][expired]" value="<?php echo !empty($banner[$domain_fix]['S1']['expired']) ? $banner[$domain_fix]['S1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'S1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí S2 (<?php echo $S2?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][S2][expired]" value="<?php echo !empty($banner[$domain_fix]['S2']['expired']) ? $banner[$domain_fix]['S2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'S2']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">E - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí E1 (<?php echo $E?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][E1][expired]" value="<?php echo !empty($banner[$domain_fix]['E1']['expired']) ? $banner[$domain_fix]['E1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'E1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí E2 (<?php echo $E?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][E2][expired]" value="<?php echo !empty($banner[$domain_fix]['E2']['expired']) ? $banner[$domain_fix]['E2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'E2']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí E3 (<?php echo $E?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][E3][expired]" value="<?php echo !empty($banner[$domain_fix]['E3']['expired']) ? $banner[$domain_fix]['E3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'E3']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí E4 (<?php echo $E?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][E4][expired]" value="<?php echo !empty($banner[$domain_fix]['E4']['expired']) ? $banner[$domain_fix]['E4']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'E4']) ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="bg-primary text-white">F - <?php echo $domain ?></legend>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F1 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F1][expired]" value="<?php echo !empty($banner[$domain_fix]['F1']['expired']) ? $banner[$domain_fix]['F1']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F1']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F2 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F2][expired]" value="<?php echo !empty($banner[$domain_fix]['F2']['expired']) ? $banner[$domain_fix]['F2']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F2']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F3 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F3][expired]" value="<?php echo !empty($banner[$domain_fix]['F3']['expired']) ? $banner[$domain_fix]['F3']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F3']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F4 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F4][expired]" value="<?php echo !empty($banner[$domain_fix]['F4']['expired']) ? $banner[$domain_fix]['F4']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F4']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F5 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F5][expired]" value="<?php echo !empty($banner[$domain_fix]['F5']['expired']) ? $banner[$domain_fix]['F5']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F5']) ?>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="col-form-label row">
                        <div class="col d-flex align-items-center m--font-bold">Vị trí F6 (<?php echo $F?>px):</div>
                        <div class="col d-flex justify-content-between align-items-center">
                            <div class="w-49">Ngày hết hạn</div>
                            <div class="w-50"><input type="text" class="w-100 datetimepicker" name="banner[<?php echo $domain_fix ?>][F6][expired]" value="<?php echo !empty($banner[$domain_fix]['F6']['expired']) ? $banner[$domain_fix]['F6']['expired'] : ""?>"></div>
                        </div>
                    </label>
                </div>
                <?php $this->load->view($this->template_path . "setting/input-banner",['domain_fix' => $domain_fix,'position' => 'F6']) ?>
            </div>
        </div>
    </fieldset>
</div>
<?php endif; ?>