
<main class="main-home">
    <div class="container">
        <div class="row main-content">
            <div class="col-xl-610">
                <div class="viewStatistic">
                    <article>
                        <div class="static-search">
                            <div class="aside-title-red text-center">
                                <h2 class="title"> Thống kê đặc biệt <?php echo $oneItem->code ?> </h2>
                            </div>
                            <div class="container form-search">
                                <form>
                                    <div class="row align-items-end">
                                        <div class="col-xl-6 pr-2 pl-2 pt-2 d-flex">
                                            <div for="chon_tinh" class="label mr-2 d-flex align-items-center">Tỉnh/Thành phố</div>
                                            <div class="select-arow flex-grow-1">
                                                <select class="form-control form-control-sm select-logan" name="chon_tinh_logan" id="selectProvince">
                                                    <option selected="selected" data-code="xsmb" data-id="1" value="/thong-ke-dac-biet-xsmb.html">Miền Bắc </option>
                                                    <?php foreach ($list_province as $item) { ?>
                                                        <option data-code="<?php echo strtolower($item->code) ?>" value="'/thong-ke-dac-biet-'. <?php echo strtolower($item->code) ?> .'.html'">
                                                            <?php echo $item->title ?>
                                                        </option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 d-flex pr-2 pl-2 pt-2">
                                            <div class="input-datetick flex-grow-1">
                                                <input id="dateEnd" class="form-control form-control-sm mr-2" name="date_end" type="text" value="<?php echo date('d/m/Y') ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 d-flex pr-2 pl-2 pt-2"> <button data-url="load_dac_biet" id="btn-Statistic-search" class="btn btn-danger btn-sm w-100 btnLogan" type="submit">Thống kê</button> </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </article>
                    <div id="view_data">
                        <article>
                            <div class="logan-result">
                                <div class="title-blue"> Hai số cuối giải đặc biệt có xác suất về cao nhất trong ngày </div>
                                <div class="table-dacbiet overflow-auto">
                                    <table class="text-center table-total">
                                        <thead>
                                            <tr>
                                                <th>Số thứ nhất</th>
                                                <th>Số thứ hai</th>
                                                <th>Số thứ ba</th>
                                                <th>Số thứ tư</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $length = round(count($data_thongke['rate'])/4);?>
                                            <?php for ($i = 0; $i < $length; $i++) { ?>
                                                <?php $c = 0 ?>
                                                <tr class="table-dacbiet-tr1 bg-white">
                                                    <?php foreach ($data_thongke['rateTop'] as $key => $item) { ?>
                                                        <?php if ($c < 4) { ?>
                                                            <td><?php echo $key ?></td>
                                                            <?php
                                                                $c++;
                                                                unset($data_thongke['rateTop'][$key]);
                                                            ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>

                                            <tr class="table-dacbiet-tr2">
                                                <td colspan="4">
                                                    <h3 class="text-center">Các kết quả mà ngày trước đó cũng có loto đặc biệt</h3>
                                                    <div class="table-thongke-time">
                                                        Ngày <?php echo date('d-m-Y', strtotime($data_thongke['data_result_today']['date'])) ?>
                                                        <span class="text-red font-20 font-weight-bold"><?php echo $data_thongke['data_result_today']['number'] ?></span> 
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="table-dacbiet-tr3 font-weight-bold">
                                                <td colspan="2">Ngày xuất hiện Loto ĐB</td>
                                                <td colspan="2">Loto ĐB ngày tiếp theo</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="p-0">
                                                    <table class="text-center w-100">
                                                        <tbody>
                                                            <tr></tr>
                                                            <tr class="font-weight-bold">
                                                                <td>Ngày</td>
                                                                <td>Giải đặc biệt</td>
                                                            </tr>
                                                            <?php foreach ($data_thongke['data_same_reward'] as $key => $item) { ?>
                                                                <tr>
                                                                    <td class="text-center"> <a rel="nofollow" href="" class="text-black"><?php echo date('d-m-Y', strtotime($key)) ?></a> </td>
                                                                    <?php
                                                                        $number_1 = substr($item, 0, 3);
                                                                        $number_2 = substr($item, -2);
                                                                    ?>
                                                                    <td class="text-center font-weight-bold"> <?php echo $number_1 ?><span class="text-red"><?php echo $number_2 ?></span> </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <table class="text-center w-100">
                                                        <tbody>
                                                            <tr></tr>
                                                            <tr class="font-weight-bold">
                                                                <td>Ngày</td>
                                                                <td>Giải đặc biệt</td>
                                                            </tr>
                                                            <?php foreach ($data_thongke['data_after_reward_1day'] as $key => $item) { ?>
                                                                <tr>
                                                                    <td class="text-center"> <a rel="nofollow" href="" class="text-black"><?php echo date('d-m-Y', strtotime($key)) ?></a> </td>
                                                                    <td class="text-center font-weight-bold"> <?php echo substr($item['number'], 0, 3) ?><span class="text-red"><?php echo $item['last_2'] ?></span> </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <h3 class="text-center font-weight-bold">Thống kê tần suất loto đặc biệt sau khi giải ĐB xuất hiện </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <table class="text-center w-100">
                                                        <thead>
                                                            <tr>
                                                                <td>Bộ số</td>
                                                                <td class="">Số lần</td>
                                                                <td>Bộ số</td>
                                                                <td class="">Số lần</td>
                                                                <td>Bộ số</td>
                                                                <td class="">Số lần</td>
                                                                <td>Bộ số</td>
                                                                <td class="">Số lần</td>
                                                                <td>Bộ số</td>
                                                                <td class="">Số lần</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="lh2">
                                                            <?php $count = round(count($data_thongke['rate'])/5);?>
                                                            <?php for ($i = 0; $i < $count; $i++) { ?>
                                                                <?php $a = 0 ?>
                                                                <tr>
                                                                    <?php foreach ($data_thongke['rate'] as $key => $item) { ?>
                                                                        <?php if ($a < 5) { ?>
                                                                            <td class="text-red font-weight-bold"><?php echo $key ?></td>
                                                                            <td class=""><?php echo $item ?> lần</td>
                                                                            <?php
                                                                                $a++;
                                                                                unset($data_thongke['rate'][$key]);
                                                                            ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            
                                            <tr class="table-dacbiet-tr2">
                                                <td colspan="4">
                                                    <h2 class="text-center">THỐNG KÊ CHẠM</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <table class="text-center w-100">
                                                        <thead>
                                                            <tr>
                                                                <td>Số</td>
                                                                <td class="text-darker">Đã về <span class="text-dark">Đầu</span></td>
                                                                <td class="text-darker">Đã về <span class="text-dark">Đuôi</span></td>
                                                                <td class="text-darker">Đã về <span class="text-dark">Tổng</span></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($data_thongke['data_cham_after_reward'] as $key => $item) { ?>
                                                                <tr class="bg-white">
                                                                    <td class="text-red font-weight-bold"> <?php echo $item['number'] ?> </td>
                                                                    <td class="text-1d"> <?php echo $item['count_head'] ?> lần </td>
                                                                    <td class="text-1d"> <?php echo $item['count_tail'] ?> lần </td>
                                                                    <td class="text-1d"> <?php echo $item['count_sum'] ?> lần </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="table-dacbiet-tr2">
                                                <td colspan="4">
                                                    <h3 class="text-center">Xem các kết quả đặc biệt đã về vào <span class="text-red">ngày tiếp theo</span> </h3>
                                                    <div class="table-thongke-time">
                                                        Ngày <?php echo date('d-m-Y', strtotime($data_thongke['data_result_today']['date'])) ?>
                                                        <span class="text-red font-20 font-weight-bold">
                                                            <?php echo $data_thongke['data_result_today']['number'] ?>
                                                        </span> 
                                                    </div>                                                
                                                </td>
                                            </tr>
                                            <tr class="font-weight-bold">
                                                <td colspan="2">Ngày xuất hiện Loto ĐB</td>
                                                <td colspan="2">Loto ĐB ngày tiếp theo</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="p-0">
                                                    <table class="text-center w-100">
                                                        <tbody>
                                                            <tr></tr>
                                                            <tr class="font-weight-bold">
                                                                <td>Ngày</td>
                                                                <td>Giải đặc biệt</td>
                                                            </tr>
                                                            <?php foreach ($data_thongke['data_same_day'] as $key => $item) { ?>
                                                                <tr>
                                                                    <td class="text-center"> <a rel="nofollow" href="" class="text-black"><?php echo date('d-m-Y', strtotime($key)) ?></a> </td>
                                                                    <?php
                                                                        $number_1 = substr($item, 0, 3);
                                                                        $number_2 = substr($item, -2);
                                                                    ?>
                                                                    <td class="text-center font-weight-bold"> <?php echo $number_1 ?><span class="text-red"><?php echo $number_2 ?></span> </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <table class="text-center w-100">
                                                        <tbody>
                                                            <tr></tr>
                                                            <tr class="font-weight-bold">
                                                                <td>Ngày</td>
                                                                <td>Giải đặc biệt</td>
                                                            </tr>
                                                            <?php foreach ($data_thongke['data_same_next_day'] as $key => $item) { ?>
                                                                <tr>
                                                                    <td class="text-center"> <a rel="nofollow" href="" class="text-black"><?php echo date('d-m-Y', strtotime($key)) ?></a> </td>
                                                                    <td class="text-center font-weight-bold"> <?php echo substr($item['number'], 0, 3) ?><span class="text-red"><?php echo $item['last_2'] ?></span> </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="table-dacbiet-tr2 font-weight-bold">
                                                <td colspan="4"> Các giải đặc biệt ngày <?php echo date('d/m') ?> hàng năm </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <table class="text-center w-100">
                                                        <tbody>
                                                            <?php foreach ($data_thongke['data_lastyear_reward_1day'] as $key => $item) { ?>
                                                                <tr>
                                                                    <td class="text-center font-weight-bold"> Năm <?php echo date('Y', strtotime($key)) ?> </td>
                                                                    <td class="text-center"> <a rel="nofollow" href="" class="text-black"><?php echo date('d-m-Y', strtotime($key)) ?></a> </td>
                                                                    <?php
                                                                        $number_1 = substr($item, 0, 3);
                                                                        $number_2 = substr($item, -2);
                                                                    ?>
                                                                    <td class="text-center font-weight-bold"> <?php echo $number_1 ?><span class="text-red"><?php echo $number_2 ?></span> </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
        </div>
    </div>
</main>
