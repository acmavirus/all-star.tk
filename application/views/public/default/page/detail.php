<?php if(!empty($oneItem)):  ?>
    <main class="container main-detail"  data-url="<?php echo getUrlPost($oneItem) ?>">
        <div class="row">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
            <article class="col-12 col-lg-8">
                <h1 class="font-23">
                    <?php echo getStringCut($oneItem->title,0,120) ?>

                </h1>
                <div class="d-flex flex-wrap justify-content-between my-3">
                    <div class="font-13 text-secondary">
		                    <?php echo (!empty($oneItem->displayed_time)) ? date_post_vn($oneItem->displayed_time) : '' ?> - <?php echo (!empty($oneItem->updated_time)) ? timeAgo($oneItem->displayed_time,"H:i") : '' ?>
                    </div>
                    <?php $this->load->view(TEMPLATE_PATH . "block/rate") ?>
                </div>
                <div class="font-14 font-weight-bold">
                    <?php echo $oneItem->description ?>
                </div>
	            <div class="content-news text-justify">
                  <?php echo getTableOfContent($oneItem->content) ?>
	            </div>

                <div class="social-share p-3 text-center border-bottom">
                    <a href="/" title="" rel="nofollow" class="btn btn-primary text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="/" title="" rel="nofollow" class="btn btn-primary text-white"><i class="fab fa-twitter"></i></a>
                    <a href="/" title="" rel="nofollow" class="btn btn-primary text-white"><i class="fas fa-envelope"></i></a>
                </div>
                <div class=""></div>
            </article>
            <?php $this->load->view(TEMPLATE_PATH . "block/sidebar") ?>
        </div>
    </main>
<?php endif; ?>