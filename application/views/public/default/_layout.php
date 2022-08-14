<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vn" lang="vn">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <?php $this->load->view(TEMPLATE_PATH . 'seo/meta_seo') ?>
  <link rel="stylesheet" type="text/css" href="<?= TEMPLATE_ASSET . 'css/main.css?v=' . ASSET_VERSION ?>" />
</head>

<body class="normal">
  <script>
    let base_url = '<?= base_url(); ?>';
  </script>
  <div class="container">
    <?php $this->load->view(TEMPLATE_PATH. "_header"); ?>
    <?= !empty($main_content) ? $main_content : '' ?>
    <?php $this->load->view(TEMPLATE_PATH. "_footer"); ?>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="<?= TEMPLATE_ASSET . 'js/custom.js?v=' . ASSET_VERSION ?>"></script>
</body>

</html>