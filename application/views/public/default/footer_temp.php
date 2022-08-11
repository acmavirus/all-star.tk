<footer class="text-white top-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="logo-footer">
                        <a href="<?php echo base_url() ?>" rel="nofollow">
                            <?php echo getThumbnailStatic((!empty($logo_footer)) ? getImageThumb($logo_footer) : TEMPLATES_ASSETS . 'assets/img/logo.svg',240,86,'logo',"img-fluid logo-footer") ?>
                        </a>
                    </div>
                    <div class="desc-footer">
                        <?php echo !empty($content_footer) ? $content_footer : ''; ?>
                    </div>
                    <ul class="data_seo">
                        <?php if (!empty($this->_settings->email)) : ?>
                            <li><b>Email: </b><span><?php echo $this->_settings->email ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($this->_settings->phone)) : ?>
                            <li><b>Phone: </b><span><?php echo $this->_settings->phone ?></span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-12 col-md-4">
                    <div class="widget widget-footer">
                        <p class="widget-title">Chuyên mục</p>
                        <?php echo navMenuFooter('nav-menu-footer','','sub-menu'); ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <?php $this->load->view(TEMPLATE_PATH . '/block/widget-post-most-view') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer text-dark text-center font-weight-bold py-3">
        All Rights Reserved. © Copyright 2021 <?php echo $settings->domain ?>
    </div>
</footer>
