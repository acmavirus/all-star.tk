<?php if (!empty($data_MB)) :
	$data_result = json_decode($data_MB['data_result']);
	if (empty($reward)) $reward = getReward(1);
	$data_loto = getLoto($data_result);
	$disTime = $data_MB['displayed_time'];
	if (empty($oneParent)) $oneParent = $oneItem;
?>
	<article class="mb-2">
		<div class="result result-single rounded">
			<?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-result', ["oneParent" => $oneParent, "disTime" => $disTime]) ?>
			<div class="table-flex-border">
				<table class="table-flex table-result" data-code="XSMB">
					<tbody>
						<tr>
							<td colspan="2">
								<div>
									<?php if (!empty($data_result[0])) {
										foreach ($data_result[0] as $value) : ?>
											<span class="text-number"><?php echo $value ?></span>
									<?php endforeach;
									} ?>
								</div>
							</td>
						</tr>
						<?php unset($data_result[0]) ?>
						<?php foreach ($data_result as $k => $item) : ?>
							<tr>
								<td><?php echo $reward[$k] ?></td>
								<td>
									<?php if (!empty($item)) foreach ($item as $number) : ?>
										<span class="text-number">
											<?php echo (!empty($number) && empty($result_status)) ? $number : '<i class="fas fa-spinner fa-pulse"></i>' ?>
										</span>
									<?php endforeach; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="100%">
								<div class="check-radio">
									<label for="showmb_02021-08-10">
										<input type="radio" checked="" id="showmb_02021-08-10" name="showmb_2021-08-10" value="0" />
										<span class="checkbox"><i class="fas fa-check"></i></span>
										<span>Đầy đủ</span>
									</label>
									<label for="showmb_22021-08-10">
										<input type="radio" id="showmb_22021-08-10" name="showmb_2021-08-10" value="2" />
										<span class="checkbox"><i class="fas fa-check"></i></span>
										<span>2 số</span>
									</label>
									<label for="showmb_32021-08-10">
										<input type="radio" id="showmb_32021-08-10" name="showmb_2021-08-10" value="3" />
										<span class="checkbox"><i class="fas fa-check"></i></span>
										<span>3 số</span>
									</label>
								</div>
								<div class="check-number">
									<ul>
										<li>0</li>
										<li>1</li>
										<li>2</li>
										<li>3</li>
										<li>4</li>
										<li>5</li>
										<li>6</li>
										<li>7</li>
										<li>8</li>
										<li>9</li>
									</ul>
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<?php if (!empty($data_loto)) : ?>
			<div class="loto loto-single">
				<table class="table table-c table-xslt-single-col" data-code="XSMB">
					<thead>
						<tr>
							<th>Đầu</th>
							<th>Lô tô</th>
							<th>Đuôi</th>
							<th>Lô tô</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data_loto as $number => $item) : ?>
							<tr>
								<td><?php echo $number ?></td>
								<td>
									<?php echo (!empty($item) && empty($result_status)) ? $item['tail'] : ''; ?>
								</td>
								<td><?php echo $number ?></td>
								<td>
									<?php echo (!empty($item) && empty($result_status)) ? $item['head'] : ''; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</article>
<?php endif; ?>