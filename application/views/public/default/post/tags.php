<main class="main-home">
	<div class="container">
		<div class="row">
			<div class="w-100 mt-2">
          <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-610">
				<h1 style="padding: 0 17px;color:#D0021B; font-size: 20px; font-weight: 500">Danh mục: <?= getStringCut($SEO['meta_title'], 0, 55) ?></h1>
				<aside id="ajax_content" class="news border-radius-4">
					<div class="new-list">
						<div class="new-list-item">
                <?php if (!empty($data)) : foreach ($data as $item) : ?>
									<div class="row no-gutters wrap">
										<div class="col-6 order-1 col-md-12 order-md-0">
											<h2 class="title">
												<a href="<?php echo getUrlPost($item) ?>" title="<?php echo $item->title ?>">
                            <?php echo $item->title ?>
												</a>
											</h2>
										</div>
										<div class="images col-6 order-0 col-md-6 order-md-1">
											<a href="<?php echo getUrlPost($item) ?>" title="<?php echo $item->title ?>">
                          <?php echo getThumbnail($item, 300, 168) ?>
											</a>
										</div>
										<div class="description col-12 order-2 col-md-6 order-md-2">
                        <?php echo $item->description ?>
										</div>
									</div>
                <?php endforeach; ?>
                <?php else: ?>
									<h3>Đang Cập nhật...</h3>
                <?php endif; ?>
						</div>
					</div>
				</aside>
				<div class="text-center">
					<button class="btn btn-danger mx-auto my-3 btnLoadMore" data-page="2" data-url="<?php echo base_url('tags/' .  $oneItem->slug) ?>" type="button">Xem thêm</button>
				</div>
			</div>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
		</div>
	</div>
</main>
