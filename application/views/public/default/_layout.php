<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>

<html lang="vi">

<head>
    <?php $this->load->view(TEMPLATE_PATH . 'seo/meta_seo') ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo $this->_settings->favicon ? getImageThumb($this->_settings->favicon, 32, 32) : site_url('public/favicon.ico') ?>">
    <link rel="stylesheet" href="<?php echo TEMPLATE_ASSET . 'css/all_minify.min.css?v=' . ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?php echo TEMPLATE_ASSET . 'css/jquery-ui.css?v=' . ASSET_VERSION ?>">
    <?= $this->_settings->style ?>
    <?= $this->_settings->script ?>
</head>

<body class="<?php echo $this->_controller; ?>">
    <div id="fb-root"></div>
    <script type='text/javascript'>
        const base_url = '<?php echo base_url(); ?>',
            media_url = '<?php echo MEDIA_URL . '/'; ?>',
            data_banner = <?php echo showImageBanner() ?>;
    </script>

    <?php $this->load->view(TEMPLATE_PATH . '_header') ?>
    <?php echo !empty($main_content) ? $main_content : '' ?>
    <?php $this->load->view(TEMPLATE_PATH . '_footer'); ?>
    <!--Wrapper End-->
    <script prefix="" type="text/javascript" src="<?php echo TEMPLATE_ASSET . 'js/all_minify.min.js?v=' . ASSET_VERSION ?>"></script>
    <script prefix="" type="text/javascript" src="<?php echo TEMPLATE_ASSET . 'js/jquery.rateit.min.js?v=' . ASSET_VERSION ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script prefix="" type="text/javascript" src="<?php echo TEMPLATE_ASSET . 'js/custom.js?v=' . ASSET_VERSION ?>"></script>
</body>

</html>