<?php
$result = getResultVietlot(43);
$maxReward_json = json_decode($result[0]['data_info']);
$jackpot1 = $maxReward_json[1][0][1];
$jackpot2 = $maxReward_json[1][1][1];
?>
<div>
	<div class="breadcrumb-table-title">
		Kết quả xổ số vietlott 655 - xs POWER 655 hôm nay
	</div>
	<div style="text-align: center; width: 96%; font-size: 22px;
    font-weight: 600; margin:8px auto">
		GIẢI THƯỞNG XS POWER 655 ƯỚC TÍNH
	</div>
	<div style="width:96%; font-size:20px;margin: 10px auto; color:blue;font-weight:bold;">GIÁ TRỊ JACKPOT 1</div>
	<div style="text-align:center;width:96%;font-size: 45px;font-weight: 700;color: red;border: 1px solid;margin: auto;background-color: #f9f9f9;border-radius: 57px;">
      <?php echo number_format($jackpot1, 0, '', '.') ?>đ
	</div>
	<div style="width:96%; font-size:20px;margin: 10px auto; color:blue;font-weight:bold;">GIÁ TRỊ JACKPOT 2</div>
	<div style="text-align:center;width:96%;font-size: 45px;font-weight: 700;color: red;border: 1px solid;margin: 15px auto;background-color: #f9f9f9;border-radius: 57px;">
      <?php echo number_format($jackpot2, 0, '', '.') ?>đ
	</div>
</div>