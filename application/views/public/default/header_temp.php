<header>
    <div class="bg-top-header">
        <div class="container d-flex justify-content-center justify-content-md-between flex-wrap">
            <?php echo navMenuTop("nav menu-header-top font-12 overflow-auto flex-nowrap text-nowrap child-border-right") ?>
        </div>
    </div>
    <!--<div class="container d-flex justify-content-center justify-content-lg-between align-items-center py-2">-->
    <!--        <a href="--><?php //echo base_url() ?><!--" title="--><?php //echo getSetting("data_seo","meta_title") ?><!--">-->
    <!--            --><?php //echo getThumbnailStatic((!empty(getSetting("data_seo","logo"))) ? getImageThumb(getSetting("data_seo","logo")) : TEMPLATES_ASSETS . 'assets/img/logo.svg','250',74,'logo',"img-fluid") ?>
    <!--        </a>-->
    <!--        --><?php //echo showContainerBanner('header',2, '') ?>
    <!--</div>-->
</header>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">
            <?php echo showContainerBanner('header_left', 2, '') ?>
        </div>
        <div class="col-12 col-md-6">
            <?php echo showContainerBanner('header_right', 2, '') ?>
        </div>
    </div>
</div>
<nav class="bg-menu navbar navbar-expand-lg disabled py-0 menu-top sticky-top  navbar-dark">
    <div class="container">
        <a href="<?php echo base_url() ?>" title="<?php echo getSetting("data_seo","meta_title") ?>">
            <?php echo getThumbnailStatic((!empty(getSetting("data_seo","logo"))) ? getImageThumb(getSetting("data_seo","logo")) : TEMPLATES_ASSETS . 'assets/img/logo.svg','250',74,'logo',"img-fluid logo-top") ?>
        </a>
        <a class="btn-lg btn py-2 rounded-0 bg-icon-home text-white" href="<?php echo base_url() ?>" title="<?php echo getSetting("data_seo","meta_title") ?>">
            <i class="fas fa-home"></i>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse offcanvas-collapse">
            <?php echo navMenuMain("navbar-nav mr-auto text-uppercase menu-pc","","dropdown-menu m-0 disabled") ?>
        </div>
    </div>
</nav>
