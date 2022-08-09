<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/30/2018
 * Time: 1:10 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <?php echo form_open("admin/setting"); ?>
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-1 order-xl-2 m--align-right">
                        <button type="submit" class="btn btn-primary m-btn m-btn--icon m-btn--air m-btn--pill btnAddForm">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    Update Setting
                                </span>
                            </span>
                        </button>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>

            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#tab_general" role="tab" aria-selected="true">
                                    <i class="la la-search"></i>
                                    Cấu hình SEO
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_banner" role="tab" aria-selected="false">
                                    <i class="la la-envelope"></i>
                                    Banner
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_contact" role="tab" aria-selected="false">
                                    <i class="la la-envelope"></i>
                                    Liên hệ
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_social" role="tab" aria-selected="false">
                                    <i class="la la-facebook"></i>
                                    Mạng xã hội
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_block" role="tab" aria-selected="false">
                                    <i class="la la-bookmark"></i>
                                    Tùy chỉnh block
                                </a>
                            </li>
                            <!--<li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_popup" role="tab" aria-selected="false">
                                    <i class="la la-picture-o"></i>
                                    Popup
                                </a>
                            </li>-->
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#tab_system" role="tab" aria-selected="false">
                                    <i class="la la-cog"></i>
                                    Cấu hình hệ thống
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab_general" role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php if(!empty($this->config->item('language_name'))) foreach ($this->config->item('language_name') as $lang_code => $lang_name): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo ($lang_code === $this->config->item('language_default')) ? 'active' : ''?>" data-toggle="tab" href="#tab_<?php echo $lang_code;?>" role="tab">
                                            <img src="<?php echo $this->templates_assets.'images/flag/'.$lang_code.'.png' ?>" alt="<?php echo $lang_name;?>">
                                            <?php echo $lang_name ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <?php if(!empty($this->config->item('language_name'))) foreach ($this->config->item('language_name') as $lang_code => $lang_name): ?>
                                    <div class="tab-pane <?php echo ($lang_code === $this->config->item('language_default')) ? 'active' : '';?>" id="tab_<?php echo $lang_code;?>" role="tabpanel">
                                        <div class="form-group">
                                            <label>Tên Website</label>
                                            <input name="meta[<?php echo $lang_code; ?>][name]"
                                                   placeholder="Tên Website"
                                                   class="form-control" type="text"
                                                   value="<?php echo isset($meta[$lang_code]['name']) ? $meta[$lang_code]['name'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Tiêu đề SEO</label>
                                            <input name="meta[<?php echo $lang_code; ?>][title]"
                                                   placeholder="Tiêu đề SEO"
                                                   class="form-control" type="text"
                                                   value="<?php echo isset($meta[$lang_code]['title']) ? $meta[$lang_code]['title'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Mô tả SEO Website</label>
                                            <textarea name="meta[<?php echo $lang_code; ?>][meta_desc]" class="form-control"><?php echo isset($meta[$lang_code]['meta_desc']) ? $meta[$lang_code]['meta_desc'] : ''; ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Từ khóa SEO Website</label>
                                            <input name="meta[<?php echo $lang_code; ?>][meta_keyword]"
                                                   placeholder="Từ khóa SEO Website"
                                                   class="form-control" type="text"
                                                   value="<?php echo isset($meta[$lang_code]['meta_keyword']) ? $meta[$lang_code]['meta_keyword'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Địa chỉ trụ sở chính</label>
                                            <input name="meta[<?php echo $lang_code; ?>][address]" class="form-control" value="<?php echo isset($meta[$lang_code]['address']) ? $meta[$lang_code]['address'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Địa chỉ văn phòng</label>
                                            <input name="meta[<?php echo $lang_code; ?>][address_office]" class="form-control" value="<?php echo isset($meta[$lang_code]['address_office']) ? $meta[$lang_code]['address_office'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Hotline</label>
                                            <input name="meta[<?php echo $lang_code; ?>][hotline]" class="form-control" value="<?php echo isset($meta[$lang_code]['hotline']) ? $meta[$lang_code]['hotline'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Email Hotline</label>
                                            <input name="meta[<?php echo $lang_code; ?>][email]" class="form-control" value="<?php echo isset($meta[$lang_code]['email']) ? $meta[$lang_code]['email'] : ''; ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="form-group">
                                    <label for="thumbnail">Favicon Website</label>
                                    <div class="input-group m-input-group m-input-group--air">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-picture-o"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="favicon" value="<?php echo !empty($favicon) ? $favicon : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
                                    </div>
                                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                        <img height="100" src="<?php echo !empty($favicon) ? getImageThumb($favicon,100,100) : '' ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail">Ảnh đại diện trang chủ Website (Social Share) (200px x 200px)</label>
                                    <div class="input-group m-input-group m-input-group--air">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-picture-o"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="homepage_thumbnail" value="<?php echo !empty($homepage_thumbnail) ? $homepage_thumbnail : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
                                    </div>
                                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                        <img height="100" src="<?php echo !empty($homepage_thumbnail) ? getImageThumb($homepage_thumbnail,100,100) : '' ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail">Logo</label>
                                    <div class="input-group m-input-group m-input-group--air">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-picture-o"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="logo" value="<?php echo !empty($logo) ? $logo : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
                                    </div>
                                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                        <img height="70" src="<?php echo !empty($logo) ? getImageThumb($logo,100,100) : '' ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail">Watermark (Ảnh PNG trong suốt)</label>
                                    <div class="input-group m-input-group m-input-group--air">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-picture-o"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="watermark" value="<?php echo !empty($watermark) ? $watermark : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
                                    </div>
                                    <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                        <img height="70" src="<?php echo !empty($watermark) ? getImageThumb($watermark,100,100) : '' ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Chat box</label>
                                    <textarea name="script_chatbox" rows="10" class="form-control"><?php echo !empty($script_chatbox) ? $script_chatbox : '' ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Chèn script ở Head</label>
                                    <textarea name="script_head" rows="10" class="form-control"><?php echo !empty($script_head) ? $script_head : '' ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Chèn script ở trước Body đóng</label>
                                    <textarea name="script_body" rows="10" class="form-control"><?php echo !empty($script_body) ? $script_body : '' ?></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_banner" role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                            <?php if(!empty($this->config->item('domain_satellite'))) foreach ($this->config->item('domain_satellite') as $key => $domain): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $key == 0 ? 'active show' : '' ?>" data-toggle="tab" href="#tab_<?php echo $domain;?>" role="tab">
                                        <strong>Cấu hình website: <?php echo $domain ?></strong>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <?php if(!empty($this->config->item('domain_satellite'))) foreach ($this->config->item('domain_satellite') as $key => $domain): ?>
                                    <div class="tab-pane <?php echo $key == 0 ? 'active show' : '' ?>" id="tab_<?php echo $domain;?>" role="tabpanel">
                                        <?php $this->load->view($this->template_path .  'setting/banner',['domain'=>$domain]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_contact" role="tabpanel">
                            <ul class="nav nav-tabs">
                                <?php if(!empty($this->config->item('language_name'))) foreach ($this->config->item('language_name') as $lang_code => $lang_name): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo ($lang_code === $this->config->item('language_default')) ? 'active' : ''?>" data-toggle="tab" href="#tab_<?php echo $lang_code;?>" role="tab">
                                            <img src="<?php echo $this->templates_assets.'images/flag/'.$lang_code.'.png' ?>" alt="<?php echo $lang_name;?>">
                                            <?php echo $lang_name ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="tab-content">
                                <?php if(!empty($this->config->item('language_name'))) foreach ($this->config->item('language_name') as $lang_code => $lang_name): ?>
                                    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>" id="tab_contact_<?php echo $lang_code; ?>">
                                        <div class="box-body">

                                            <div class="form-group">
                                                <label>Tên công ty</label>
                                                <input name="contact[<?php echo $lang_code; ?>][title]"
                                                       placeholder="Tên công ty"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['title']) ? $contact[$lang_code]['title'] : ''; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label>Trụ sở chính</label>
                                                <input name="contact[<?php echo $lang_code; ?>][address]"
                                                       placeholder="Địa chỉ"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['address']) ? $contact[$lang_code]['address'] : ''; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label>Mua hàng & CSKH</label>
                                                <input name="contact[<?php echo $lang_code; ?>][phone]"
                                                       placeholder="Mua hàng & CSKH"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['phone']) ? $contact[$lang_code]['phone'] : ''; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label>Tư vấn kỹ thuật</label>
                                                <input name="contact[<?php echo $lang_code; ?>][phone1]"
                                                       placeholder="Tư vấn kỹ thuật"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['phone1']) ? $contact[$lang_code]['phone1'] : ''; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Liên hệ làm đại lý</label>
                                                <input name="contact[<?php echo $lang_code; ?>][phone2]"
                                                       placeholder="Liên hệ làm đại lý"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['phone2']) ? $contact[$lang_code]['phone2'] : ''; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Mời quảng cáo</label>
                                                <input name="contact[<?php echo $lang_code; ?>][phone3]"
                                                       placeholder="Mời quảng cáo"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['phone3']) ? $contact[$lang_code]['phone3'] : ''; ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input name="contact[<?php echo $lang_code; ?>][email]"
                                                       placeholder="mail"
                                                       class="form-control" type="text"
                                                       value="<?php echo !empty($contact[$lang_code]['email']) ? $contact[$lang_code]['email'] : ''; ?>"/>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="form-group">
                                    <label>Địa chỉ Google maps </label>
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <input name="contact[maps_latitude]"
                                                   placeholder="Kinh độ (latitude)"
                                                   class="form-control" type="text"
                                                   value="<?php echo !empty($contact['maps_latitude']) ? $contact['maps_latitude'] : ''; ?>"/>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <input name="contact[maps_longitude]"
                                                   placeholder="Vĩ độ (longitude)"
                                                   class="form-control" type="text"
                                                   value="<?php echo !empty($contact['maps_longitude']) ? $contact['maps_longitude'] : ''; ?>"/>
                                        </div>
                                        <!-- <div class="col-sm-12 col-xs-12">
                                        <input name="contact[url_map]"
                                               placeholder="url map"
                                               class="form-control" type="text"
                                               value="<?php echo !empty($contact['url_map']) ? $contact['url_map'] : ''; ?>"/>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_social" role="tabpanel">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Facebook</label>
                                    <input name="social_facebook" placeholder="Facebook Url"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_facebook) ? $social_facebook : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Google</label>
                                    <input name="social_google"
                                           placeholder="Google"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_google) ? $social_google : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Twitter</label>
                                    <input name="social_twitter"
                                           placeholder="Twitter"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_twitter) ? $social_twitter : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Youtube</label>
                                    <input name="social_youtube"
                                           placeholder="Youtube"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_youtube) ? $social_youtube : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Instagram</label>
                                    <input name="social_instagram"
                                           placeholder="Instagram"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_instagram) ? $social_instagram : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Tumblr</label>
                                    <input name="social_tumblr"
                                           placeholder="Tumblr"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_tumblr) ? $social_tumblr : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Linkedin</label>
                                    <input name="social_linkedin"
                                           placeholder="Linkedin"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_linkedin) ? $social_linkedin : ''; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Pinterest</label>
                                    <input name="social_pinterest"
                                           placeholder="Pinterest"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_pinterest) ? $social_pinterest : ''; ?>"/>
                                </div><div class="form-group">
                                    <label>Telegram</label>
                                    <input name="social_Telegram"
                                           placeholder="Telegram"
                                           class="form-control" type="text"
                                           value="<?php echo isset($social_telegram) ? $social_telegram : ''; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_block" role="tabpanel">
                            <div class="form-group">
                                <label>Thông báo đầu trang</label>
                                <textarea name="block[header_top]" class="form-control summernote"><?php echo !empty($block['header_top']) ? $block['header_top'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_popup" role="tabpanel">
                            <div class="form-group">
                                <label for="thumbnail">Banner Popup</label>
                                <div class="input-group m-input-group m-input-group--air">
                                    <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-picture-o"></i>
                                            </span>
                                    </div>
                                    <input type="text" name="block[popup][image]" value="<?php echo !empty($block['popup']['enable']) ? $block['popup']['enable'] : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
                                </div>
                                <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                                    <img width="100" height="100" src="<?php echo !empty($block['popup']['enable']) ? getImageThumb($block['popup']['enable'],100,100) : '' ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Bật tắt quảng cáo</label>
                                <div class="m-input">
                                    <input data-switch="true" type="checkbox" name="block[popup][enable]" class="switchBootstrap" <?php echo !empty($block['popup']['enable']) ? 'checked="checked"' : '' ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Link</label>
                                <input type="text" name="block[popup][link]" placeholder="Link" value="<?php echo !empty($block['popup']['link']) ? $block['popup']['link'] : '' ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Thời gian hiện khi vào trang web (Nhập số giây)</label>
                                <input type="number" name="block[popup][time_start]" value="<?php echo !empty($block['popup']['time_start']) ? $block['popup']['time_start'] : '30' ?>" class="form-control">
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_system" role="tabpanel">
                            <div class="form-group">
                                <label>Bật ReCaptcha</label>
                                <div class="m-input">
                                    <input data-switch="true" type="checkbox" name="recaptcha_mode" class="switchBootstrap" <?php echo !empty($recaptcha_mode) ? 'checked="checked"' : '' ?>>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email quản trị</label>
                                <input type="text" name="email_admin"
                                       placeholder="Email quản trị"
                                       class="form-control"
                                       value="<?php echo isset($email_admin) ? $email_admin : ''; ?>"/>
                            </div>

                            <div class="form-group">
                                <label>Protocol</label>
                                <input type="text" name="protocol" placeholder="Protocol" class="form-control"
                                       value="<?php echo isset($protocol) ? $protocol : ''; ?>"/>
                            </div>


                            <div class="form-group">
                                <label>SMTP Host</label>
                                <input type="text" name="smtp_host" placeholder="SMTP Host" class="form-control"
                                       value="<?php echo isset($smtp_host) ? $smtp_host : ''; ?>"/>
                            </div>

                            <div class="form-group">
                                <label>SMTP User</label>
                                <input type="text" name="smtp_user" placeholder="SMTP User" class="form-control"
                                       value="<?php echo isset($smtp_user) ? $smtp_user : ''; ?>"/>
                            </div>

                            <div class="form-group">
                                <label>SMTP Password</label>
                                <input type="text" name="smtp_pass" placeholder="SMTP Password" class="form-control"
                                       value="<?php echo isset($smtp_pass) ? $smtp_pass : ''; ?>"/>
                            </div>

                            <div class="form-group">
                                <label>SMTP Port</label>
                                <input type="text" name="smtp_port" placeholder="SMTP Port" class="form-control"
                                       value="<?php echo isset($smtp_port) ? $smtp_port : ''; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    var url_ajax_backup_db = '<?php echo site_url('admin/setting/ajax_backup_db')?>',
        url_ajax_restore_db = '<?php echo site_url('admin/setting/ajax_restore_db')?>',
        url_ajax_delete_db = '<?php echo site_url('admin/setting/ajax_delete_db')?>'
    ;
</script>