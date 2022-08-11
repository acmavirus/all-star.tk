<div class="container">
	<div style="margin-top: 8px;width: 100%" class="text-center">
		<h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
	</div>
</div>
<?php if (!empty($oneItem)) : ?>
	<main>
		<div class="container">
			<?php if (!empty($listweekday)) : ?>
				<div class="row">
					<div class="w-100">
						<div class="submenu2">
							<ul class="text-center text-capitalize rounded lh25 submenu2-bg colum-4 pl-0 pl-md-3">
								<li class="active">
									<a href="<?php echo getUrlCategory($oneItem) ?>" title="<?php echo $oneItem->title ?>"><?php echo $oneItem->code ?></a>
								</li>
								<?php foreach ($listweekday as $item) : ?>
									<li class="mx-md-4">
										<a href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->title ?>"><?php echo $item->title ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="w-100 mt-2">
					<?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-610">
					<?php $this->load->view(TEMPLATE_PATH . 'lottery/_' . strtolower($oneItem->code) . '_game', $data, $data_current, $oneItem) ?>
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


<div class="modal" id="game-modal" tabindex="-1" role="dialog">
	<div style="margin-top: 70px;" class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 style="font-size: 26px;" class="modal-title">Xem kết quả</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>
					<span>Đang lấy dữ liệu ...</span>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
			</div>
		</div>
	</div>
</div>