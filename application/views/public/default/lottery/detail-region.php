<div class="container">
	<div style="margin-top: 8px;width: 100%" class="text-center">
		<h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
	</div>
</div>
<?php if (!empty($oneItem)) : ?>
	<main>
		<div class="container">
			<?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-weekday') ?>
			<div class="row">
				<div class="w-100 mt-2">
					<?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-610">
					<div id="ajax_content">
						<?php if (!empty($data_region)) {
							if ($oneItem->code == 'XSMB') : ?>
								<?php foreach ($data_region as $key => $item) : ?>
									<?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['data_MB' => $data_region[$key]]) ?>
								<?php endforeach; ?>
							<?php else : ?>
								<?php foreach ($data_region as $key => $item) :  ?>
									<?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_region[$key]]) ?>
								<?php endforeach; ?>
						<?php endif;
						} ?>
					</div>
					<button class="btnLoadMore mt-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>
					<?php if (!empty($oneItem->content)) : ?>
						<div class="new-content bg-white mt-3 p-3 text-justify">
							<?php echo $oneItem->content; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
				<?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
			</div>
		</div>
	</main>
<?php else : ?>
	<article class="my-2">
		<h3>Đang cập nhật</h3>
	</article>
<?php endif; ?>