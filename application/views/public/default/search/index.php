<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
    </div>
</div>
<?php if (!empty($oneItem)) : ?>
    <main class="container">
        <div class="row">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
            <div class="col-12 col-lg-8">
                <script async src="https://cse.google.com/cse.js?cx=65fee482ccdc39665"></script>
                <div class="gcse-search"></div>
            </div>
            <?php $this->load->view(TEMPLATE_PATH . "block/sidebar") ?>
        </div>
    </main>
<?php endif; ?>