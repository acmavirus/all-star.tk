<div class="container">
	<div style="margin-top: 8px;width: 100%" class="text-center">
		<h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
	</div>
</div>
<main class="main-home">
	<div class="container">
		<div class="row">
			<div class="w-100 mt-2">
          <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
			</div>
		</div>
		<div class="row main-content">
			<div class="col-xl-610">
          <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_645') ?>
          <?php $this->load->view(TEMPLATE_PATH . "lottery/_table_vietlot_645", ['oneParent' => $parent_645, 'one_data_vietlot' => $data_vietlot_645[0]]) ?>
				<div class="px-2 mt-3">
					<p>
						Kết quả xổ số vietlott - XS Mega 645 mở thưởng thứ 4/6/Chủ Nhật hàng tuần. Giá vé chỉ từ 10.000, mua vé bạn sẽ có cơ hội trúng Giải JACKPOT trị giá trên <b>12 tỷ đồng</b>
					</p>
				</div>
          <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_655') ?>
          <?php $this->load->view(TEMPLATE_PATH . "lottery/_table_vietlot_655", ['oneParent' => $parent_655, 'one_data_vietlot' => $data_vietlot_655[0]]) ?>
				<div class="px-2 mt-3">
					<p>
						Kết quả xổ số vietlott - XS Power 655 mở thưởng thứ 3/5/7 hàng tuần. Giá vé chỉ từ 10.000, mua vé bạn sẽ có cơ hội trúng Giải JACKPOT trị giá trên <b>12 tỷ đồng</b>
					</p>
				</div>
          <?php $this->load->view(TEMPLATE_PATH . "lottery/_table_vietlot_3d", ['oneParent' => $parent_3d, 'one_data_vietlot' => $data_vietlot_3d[0]]) ?>
				<div class=px-2 mt-3">
					<p>
						Kết quả xổ số vietlott - XS Mega 645 mở thưởng thứ 4/6/Chủ Nhật hàng tuần. Giá vé chỉ từ 10.000, mua vé bạn sẽ có cơ hội trúng Giải JACKPOT trị giá trên <b>12 tỷ đồng</b>
					</p>
				</div>
          <?php $this->load->view(TEMPLATE_PATH . "lottery/_table_vietlot_4d", ['oneParent' => $parent_4d, 'one_data_vietlot' => $data_vietlot_4d[0]]) ?>
				<div class="px-2 mt-3">
					<p>
						Kết quả xổ số vietlott - XS Max4d mở thưởng thứ 3/5/7 hàng tuần. Giá vé chỉ từ 10.000, mua vé bạn sẽ có cơ hội trúng Giải nhất trị giá trên <b>15 triệu đồng</b>
					</p>
				</div>
			</div>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
		</div>
      <?php $this->load->view(TEMPLATE_PATH . 'block/_list-post') ?>

	</div>

</main>