<?php if (!empty($data_MT_MN)) :
	if (empty($reward)) {
		$reward = getReward(2);
	}
	$disTime = $data_MT_MN[0]['displayed_time'];
	if (empty($oneParent)) $oneParent = $oneItem;
	$code = strtoupper($oneParent->code);

?>
	<article class="mb-2">
		<div class="result result-multi rounded">
			<?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-result', ["oneParent" => $oneParent, "disTime" => $disTime]) ?>
			<div class="table-flex-border">
				<table class="table-flex table-result" data-code="<?php echo $code; ?>">
					<tbody>
						<tr>
							<td></td>
							<?php foreach ($data_MT_MN as $value) :
								$oneItem = getCateById($value['category_id']);
							?>
								<td>
									<a href="<?php echo getUrlCategory($oneItem) ?>" title="<?php echo $oneItem->title ?>"><?php echo $oneItem->title ?></a>
								</td>
							<?php endforeach; ?>
						</tr>
						<?php foreach ($reward as $k => $titleReward) : ?>
							<tr>
								<td><?php echo $titleReward ?></td>
								<?php foreach ($data_MT_MN as $item) :
									$dataTable = json_decode($item['data_result'], true);
								?>
									<td>
										<?php if (!empty($dataTable[$k])) foreach ($dataTable[$k] as $number) : ?>
											<span class="text-number">
												<?php echo (!empty($number) && empty($result_status)) ? $number : '<i class="fas fa-spinner fa-pulse"></i>' ?>
											</span>
										<?php endforeach; ?>
									</td>
								<?php $dataTable = null;
								endforeach; ?>
							</tr>
						<?php endforeach; ?>

					</tbody>
					<tfoot>
						<tr>
							<td colspan="100%">
								<div class="check-radio">
									<label for="<?php echo $code . '-0-' . $disTime ?>">
										<input type="radio" checked="" id="<?php echo $code . '-0-' . $disTime ?>" name="show_XSMT_2021-08-12" value="0">
										<span class="checkbox"><i class="fas fa-check"></i></span>
										<span>Đầy đủ</span>
									</label>
									<label for="<?php echo $code . '-1-' . $disTime ?>">
										<input type="radio" id="<?php echo $code . '-1-' . $disTime ?>" name="show_XSMT_2021-08-12" value="2">
										<span class="checkbox"><i class="fas fa-check"></i></span>
										<span>2 số</span>
									</label>
									<label for="<?php echo $code . '-3-' . $disTime ?>">
										<input type="radio" id="<?php echo $code . '-3-' . $disTime ?>" name="show_XSMT_2021-08-12" value="3">
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
		<?php if (!empty($data_MT_MN)) : ?>
			<div class="loto loto-multi rounded">
				<table class="table table-c table-xslt-multi-col" data-code="<?php echo $code; ?>">
					<thead>
						<tr>
							<th>Đầu</th>
							<?php
							foreach ($data_MT_MN as $i => $item) :
								$oneCateItem = getCateById($item['category_id']);
							?>
								<th>
									<?php echo $oneCateItem->title ?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php for ($k = 0; $k <= 9; $k++) : ?>
							<tr>
								<td><?php echo $k ?></td>
								<?php foreach ($data_MT_MN as $item) :  $dataLoto = getLoto(json_decode($item['data_result'], true)); ?>
									<td>
										<?php
										echo (!empty($dataLoto[$k]['tail']) && empty($result_status)) ? $dataLoto[$k]['tail'] : ''; ?>
									</td>
								<?php $dataLoto = null;
								endforeach; ?>
							</tr>
						<?php endfor; ?>
					</tbody>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</article>
<?php endif; ?>