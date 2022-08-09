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
                                        <i class="la la-language"></i>Thông tin
                                    </a>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tab_language" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input name="url" placeholder="URL" class="form-control" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Keyword:</label>
                                            <label for="title"><span class="count-key">0</span> / <?php echo $this->config->item('SEO_keyword_maxlength') ?></label>
                                            <input name="meta_keyword" placeholder="Meta Keyword" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Tiêu đề</label>
                                            <input name="title" placeholder="Tiêu đề" class="form-control" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Tiêu đề SEO (Meta Title)</label>
                                            <label for="title"><span class="count-title">0</span> / <?php echo $this->config->item('SEO_title_maxlength') ?></label>
                                            <input name="meta_title" placeholder="Meta Title" class="form-control" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Tóm tắt</label>
                                            <textarea name="description" placeholder="Tóm tắt " class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Mô tả SEO (Meta Description)</label>
                                            <label for="desc"><span class="count-desc">0</span> / <?php echo $this->config->item('SEO_description_maxlength') ?></label>
                                            <textarea name="meta_description" placeholder="Meta Description" class="form-control" rows="5" ></textarea>
                                        </div>
                                    </div>

                                    <!--<div class="col-12 col-sm-6">
                                        <?php /*$this->load->view(TEMPLATE_PATH.'_block/seo_meta') */?>
                                    </div>-->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <textarea name="content" class="form-control tinymce"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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
<!-- <script type="text/javascript">
    var url_ajax_load_category = '<?php echo site_admin_url('category/ajax_load/') . $this->_method ?>';
    var url_ajax_load_match = '<?php echo site_admin_url('post/ajax_load_match') ?>';
</script> -->