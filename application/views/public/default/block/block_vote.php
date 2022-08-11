<?php if (!empty($oneItem)) : ?>
    <?php $this->load->helper('regex'); ?>

    <?php //dd($oneItem); ?>
    <?php
    $data_dealer = json_decode($oneItem->data_dealer);
    isset($data_dealer->title) ? $title = $data_dealer->title : $title = $oneItem->title;
    isset($data_dealer->advantages) ? $advantages = $data_dealer->advantages : $advantages = $oneItem->title;
		(isset($data_dealer->reliability) && !is_null($data_dealer->reliability)) ? $reliability = $data_dealer->reliability : $reliability = 0;
    (isset($data_dealer->odds) && !is_null($data_dealer->odds)) ? $odds = $data_dealer->odds : $odds = 0;
		(isset($data_dealer->bonus) && !is_null($data_dealer->bonus)) ? $bonus = $data_dealer->bonus : $bonus = 0;
		(isset($data_dealer->payment_speed) && !is_null($data_dealer->payment_speed)) ? $payment_speed = $data_dealer->payment_speed : $payment_speed = 0;
		(isset($data_dealer->customer_care) && !is_null($data_dealer->customer_care)) ? $customer_care = $data_dealer->customer_care : $customer_care = 0;
    $sum = ($reliability + $odds + $bonus + $payment_speed + $customer_care);
    $total = $sum / 5;
    ?>

	<div class="game-vote">
		<h1 class="post-title">
				<?php echo substr($oneItem->title,0,120) ?>
		</h1>
		<div class="mb-4 d-block d-sm-flex">
			<div class="text-center icon">
				<img src="<?php echo getImageThumb($oneItem->thumbnail) ?>" alt="<?php echo $oneItem->title ?>" class="img-fluid lazyloaded" data-ll-status="loaded">
			</div>
			<div class="game-vote-info">
				<h2 class="post-title"><?php echo $title ?></h2>
				<div class="game-vote-info-rate">
            <?php $this->load->view(TEMPLATE_PATH . '/block/rate') ?>
				</div>
				<div class="game-vote-info-advantages">
            <?php echo $advantages ?>
				</div>
			</div>
		</div>
		<table class="uk-table table-2">
			<tbody>
			<tr>
				<td>
					<div class="txt">Độ tin cậy</div>
				</td>
				<td>
					<div class="rate-2">
            <?php echo renderStar($reliability)?>
					</div>
				</td>
				<td><?php echo $reliability ?></td>
			</tr>
			<tr>
				<td>
					<div class="txt">Tỷ lệ cược</div>
				</td>
				<td>
					<div class="rate-2">
              <?php echo renderStar($odds)?>
					</div>
				</td>
				<td><?php echo $odds ?></td>

			</tr>
			<tr>
				<td>
					<div class="txt">Tiền thưởng</div>
				</td>
				<td>
					<div class="rate-2">
              <?php echo renderStar($bonus)?>
					</div>
				</td>
				<td><?php echo $bonus ?></td>
			</tr>
			<tr>
				<td>
					<div class="txt">Tốc độ thanh toán</div>
				</td>
				<td>
					<div class="rate-2">
              <?php echo renderStar($payment_speed)?>
					</div>
				</td>
				<td><?php echo $payment_speed ?></td>
			</tr>
			<tr>
				<td>
					<div class="txt pb-3">Chăm sóc khách hàng</div>
				</td>
				<td>
					<div class="rate-2 pb-3">
              <?php echo renderStar($customer_care)?>
					</div>
				</td>
				<td><?php echo $customer_care ?></td>
			</tr>
			<tr>
				<td>
					<div class="txt">Tổng điểm</div>
				</td>
				<td>
					<div class="rate-2">
              <?php echo renderStar($total)?>
					</div>
				</td>
				<td><?php echo $total ?></td>
			</tr>
			</tbody>
		</table>
	</div>
<?php endif; ?>
