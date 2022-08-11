<?php if (!empty($one_data_vietlot)) :
    $data_result = json_decode($one_data_vietlot['data_result']);
    if (empty($reward)) {
        $reward = getReward(44);
    }
    $disTime = $one_data_vietlot['displayed_time'];
    if (empty($oneParent->code)) {
        $oneParent = $oneItem;
    }
    ?>
	<style>
    .ketqua table td {
      border: 1px #e8e8e8 solid;
      padding: 5px;
      font-family: Arial, sans-serif;
      text-align: center;
    }
    .g_db {
      font-size: 33px;
      color: red;
      font-weight: 700;
    }
    .bg_tr {
      background-color: #f9f9f9;
    }
	</style>
	<article class="mb-2">
		<div class="ketqua">
			<h2 class="breadcrumb-table-title">
				<a href="<?php echo getUrlCategory($oneParent) ?>" title="<?php echo $oneParent->title?>"><?php echo $oneParent->code?></a>
				»
				<a href="<?php echo getUrlWeekday($oneParent, date('w', strtotime($disTime))); ?>" title="<?php echo $oneParent->code?> <?php echo getDayOfWeek($disTime) ?>"><?php echo $oneParent->code?> <?php echo getDayOfWeek($disTime) ?></a>
				»
				<a href="max4d-<?php echo date('d-m-Y', strtotime($disTime)); ?>" title="<?php echo $oneParent->code?> <?php echo date('d/m/Y', strtotime($disTime)); ?>"><?php echo $oneParent->code?> <?php echo date('d/m/Y', strtotime($disTime)); ?></a>
			</h2>
			<div class="border">
				<table width="100%">
					<tbody>
					<tr>
						<td style="border-left:0">
							<span class="font-16">Giải Nhất</span>
							<br>
							<p style="font-size:12px;">1.500 lần</p>
						</td>
						<td style="border-right:0;">
							<span class="g_db">
									<?php
									$number = str_replace("X", "", implode("", $data_result[0]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
							</span>
						</td>
					</tr>

					<tr class="bg_tr">
						<td style="border-left:0">
							<span class="font-16">Giải Nhì</span>
							<br>
							<p style="font-size:12px;">650 lần</p>
						</td>
						<td style="border-right:0;">
							<table width="100%" border="0">
								<tbody>
								<tr>
									<td style="border:0;">
										<?php
										$number = str_replace("X", "", implode("", $data_result[1][0]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
									</td>
									<td style="border:0;">
										<?php
										$number = str_replace("X", "", implode("", $data_result[1][1]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
									</td>

								</tr>
								</tbody>
							</table>

						</td>
					</tr>
					<tr>
						<td style="border-left:0">
							<span class="font-16">Giải ba</span>
							<br>
							<p style="font-size:12px;">300 lần</p>
						</td>
						<td style="border-right:0;">
							<table width="100%" border="0">
								<tbody>
								<tr>
									<td style="border:0;">
										<?php
											$number = str_replace("X", "", implode("", $data_result[2][0]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
									</td>
									<td style="border:0;">
										<?php
											$number = str_replace("X", "", implode("", $data_result[2][1]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
									</td>
									<td style="border:0;">
										<?php
											$number = str_replace("X", "", implode("", $data_result[2][2]));
                   echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
									?>
									</td>
								</tr>

								</tbody>
							</table>

						</td>
					</tr>
					<tr class="bg_tr">
						<td style="border-left:0">
							<span class="font-16">Giải KK1</span>
							<br>
							<p style="font-size:12px;">100 lần</p>
						</td>
						<td style="border-right:0;">
							<table width="100%" border="0">
								<tbody>
								<tr>
									<td style="border:0;">
                      <?php
                      $number = str_replace("X", "", implode("", $data_result[3]));
                      echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
                      ?>
									</td>

								</tr>
								</tbody>
							</table>

						</td>
					</tr>
					<tr>
						<td style="border-left:0">
							<span class="font-16">Giải KK2</span>
							<br>
							<p style="font-size:12px;">10 lần</p>
						</td>
						<td style="border-right:0;">
							<table width="100%" border="0">
								<tbody>
								<tr>
									<td style="border:0;">
                      <?php
                      $number = str_replace("X", "", implode("", $data_result[4]));
                      echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>';
                      ?>
									</td>
								</tr>
								</tbody>
							</table>

						</td>
					</tr>

					</tbody>
				</table>
			</div>
		</div>
	</article>
<?php endif; ?>