<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-8">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="m-form__group--inline">
                                    <select name="category_id" class="form-control m-select2 filter_category" style="width: 100%;"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        <?php echo button_admin(['add','delete']) ?>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable" id="ajax_data"></div>
            <!--end: Datatable -->
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="formModalLabel">Form</h3>
            </div>
            <div class="modal-body">
                <?php echo form_open('',['id'=>'','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed m-form--state']) ?>
                <input type="hidden" name="id" value="0">
                <div class="m-portlet--tabs">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#tab_language" role="tab" aria-selected="true">
                                        <i class="la la-language"></i>N???i dung SEO
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_info" role="tab" aria-selected="false">
                                        <i class="la la-info"></i>Th??ng tin
                                    </a>
                                </li>
                                <?php if ($this->_controller === 'post' && $this->_method === 'soikeo') : ?>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_sk" role="tab" aria-selected="false">
                                            <i class="la la-info"></i>Soi k??o
                                        </a>
                                    </li>
                                <?php endif ?>

                                <?php if (in_array($this->_method,['nhacai','gamebai','banca'])) : ?>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_nhacai" role="tab" aria-selected="false">
                                            <i class="la la-info"></i>Nh?? c??i
                                        </a>
                                    </li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tab_language" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Ti??u ?????</label>
                                            <input name="title" placeholder="Ti??u ????? " class="form-control" type="text" />
                                        </div>
                                        <div class="form-group">
                                            <label>T??m t???t</label>
                                            <textarea name="description" placeholder="T??m t???t " class="form-control" rows="24"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <?php $this->load->view(TEMPLATE_PATH.'_block/seo_meta') ?>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>N???i dung</label>
                                            <textarea name="content" class="form-control tinymce"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label>Danh m???c ch??nh:</label>
                                            <select name="category_primary_id" class="form-control m-select2 category_primary" style="width: 100%;"></select>

                                        </div>
                                        <div class="form-group">
                                            <label>Danh m???c:</label>
                                            <select name="category_id[]" class="form-control m-select2 category" style="width: 100%;"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tag: </label>
                                            <select name="tag_id[]" class="form-control m-select2 tag" style="width: 100%;"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tr???ng th??i:</label>
                                            <div class="m-input">
                                                <input data-switch="true" type="checkbox" name="is_status" class="switchBootstrap" checked="checked">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>N???i b???t:</label>
                                            <div class="m-input">
                                                <input data-switch="true" type="checkbox" name="is_featured" class="switchBootstrap">
                                            </div>
                                        </div>

                                        <!--<div class="form-group">-->
                                        <!--    <label>Robots:</label>-->
                                        <!--    <div class="m-input">-->
                                        <!--        <input data-switch="true" type="checkbox" name="is_robot" class="switchBootstrap" checked="checked">-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <?php if ($this->_controller === 'post' && $this->_method === 'video') : ?>
                                            <div class="video">
                                                <div class="form-group">
                                                    <label>Link video</label>
                                                    <textarea name="video" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label>Gi??? ????ng</label>
                                            <div class="input-group date">
                                                <input name="displayed_time" type="text" class="form-control m-input" id="m_datetimepicker_3">
                                                <div class="input-group-append">
													<span class="input-group-text">
														<i class="la la-calendar glyphicon-th"></i>
													</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="thumbnail">???nh ?????i di???n</label>
                                            <div class="input-group m-input-group m-input-group--air">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="input_thumbnail">
                                                        <i class="la la-picture-o"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="thumbnail" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click ????? ch???n ???nh" aria-describedby="input_thumbnail">
                                            </div>
                                            <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                                <img width="100" height="100" src="<?php echo getImageThumb('',100,100) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($this->_controller === 'post' && $this->_method === 'soikeo') : ?>
                                <div class="tab-pane" id="tab_sk" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Match ID</label>
                                                <select name="match_id" class="form-control m-select2 match" style="width: 100%;"></select>
                                            </div>
                                            <div class="form-group">
                                                <label>T??? l??? k??o</label>
                                                <div class="input-group">
                                                    <textarea rows="10" type="text" class="form-control" name="data_bets"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endif ?>
                            <?php if (in_array($this->_method,['nhacai','gamebai','banca'])) : ?>
                                <div class="tab-pane" id="tab_nhacai" role="tabpanel">
                                    <div class="row">

                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label>S??? th??? t???</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="order">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label>T??n nh?? c??i</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data_dealer[title]">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label>Link ?????t c?????c</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data_dealer[link_bet]">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12 col-md-4">
                                            <label>Khuy???n m??i</label>
                                            <textarea placeholder="khuy???n m??i" class="form-control" rows="10" name="data_dealer[promote]"></textarea>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label>??u ??i???m</label>
                                            <textarea placeholder="??u ??i???m" class="form-control" rows="10" name="data_dealer[advantages]"></textarea>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label>Nh?????c ??i???m</label>
                                            <textarea placeholder="nh?????c ??i???m" class="form-control" rows="10" name="data_dealer[defect]"></textarea>
                                        </div>

		                                    <div class="col-12 col-md-4">
			                                    <div class="form-group">
				                                    <label>????? tin c???y</label>
				                                    <div class="input-group">
					                                    <input type="text" class="form-control" name="data_dealer[reliability]">
				                                    </div>
			                                    </div>
		                                    </div>

		                                    <div class="col-12 col-md-4">
			                                    <div class="form-group">
				                                    <label>T??? l??? c?????c</label>
				                                    <div class="input-group">
					                                    <input type="text" class="form-control" name="data_dealer[odds]">
				                                    </div>
			                                    </div>
		                                    </div>

		                                    <div class="col-12 col-md-4">
			                                    <div class="form-group">
				                                    <label>Ti???n th?????ng</label>
				                                    <div class="input-group">
					                                    <input type="text" class="form-control" name="data_dealer[bonus]">
				                                    </div>
			                                    </div>
		                                    </div>

		                                    <div class="col-12 col-md-4">
			                                    <div class="form-group">
				                                    <label>T???c ????? thanh to??n</label>
				                                    <div class="input-group">
					                                    <input type="text" class="form-control" name="data_dealer[payment_speed]">
				                                    </div>
			                                    </div>
		                                    </div>

		                                    <div class="col-12 col-md-4">
			                                    <div class="form-group">
				                                    <label>Ch??m s??c kh??ch h??ng</label>
				                                    <div class="input-group">
					                                    <input type="text" class="form-control" name="data_dealer[customer_care]">
				                                    </div>
			                                    </div>
		                                    </div>
                                    </div>
                                </div>

                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnSave">Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var url_ajax_load_category = '<?php echo site_admin_url('category/ajax_load/') . $this->_method ?>';
    var url_ajax_load_match = '<?php echo site_admin_url('post/ajax_load_match') ?>';
</script>