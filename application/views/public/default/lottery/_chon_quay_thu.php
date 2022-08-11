
<main>
    <div class="container">
        <aside class="mb-2 border-radius-4">
            <div class="aside-title-red text-center">
                <h3 class="title"> QUAY THỬ XỔ SỐ </h3>
            </div>
            <div class="container-fluid mb-4 mt-3 spin-search">
                <form>
                    <div class="row align-items-end">
                        <div class="col-12 text-center">
                            <div class="check-radio">
                                <?php if ($oneItem->parent_id == 0) { ?>
                                    <label for="list_region">
                                        <input type="radio" checked="" id="list_region" value="1" name="quaythu">
                                        <span class="checkbox"><i class="fas fa-check"></i></span>
                                        <span>Quay thử theo miền</span>
                                    </label>
                                    <label for="list_province">
                                        <input type="radio" id="list_province" name="quaythu">
                                        <a class="text-black2" href="<?=getUrlQuayThu($list_province[0]->code)?>" title="Quay thử <?=$list_province[0]->title?>">
                                            <span class="checkbox"><i class="fas fa-check"></i></span>
                                            <span>Quay thử theo tỉnh</span>
                                        </a>
                                    </label>
                                <?php } else { ?>
                                    <label for="list_region">
                                        <input type="radio" id="list_region" name="quaythu">
                                        <a class="text-black2" href="<?=getUrlQuayThu($list_parent[0]->code)?>" title="Quay thử <?=$list_parent[0]->title?>">
                                            <span class="checkbox"><i class="fas fa-check"></i></span>
                                            <span>Quay thử theo miền</span>
                                        </a>
                                    </label>
                                    <label for="list_province">
                                        <input type="radio" checked="" id="list_province" value="2" name="quaythu">
                                        <span class="checkbox"><i class="fas fa-check"></i></span>
                                        <span>Quay thử theo tỉnh</span>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center spin-select p-0 mt-3">
                            Chọn miền
                            <?php
                                if ($oneItem->parent_id == 0)
                                    $show_province = $list_parent;
                                else
                                    $show_province = $list_province;
                            ?>
                            <select class="selected custom-select text-form w-50 text-capitalize" id="selectProvince">
                                <?php foreach ($show_province as $item) { ?>
                                    <option value="<?php echo getUrlQuayThu($item->code)?>" <?php echo $item->code == $oneItem->code ? 'selected' : ''?>>
                                        <?php echo $item->title ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <!-- <select class="selected custom-select text-form w-50 text-capitalize d-none">
                                <option value="">Miền bắc</option>
                                <option value="">Miền trung</option>
                                <option value="">Miền Nam</option>
                            </select> -->

                            <!-- <select class="selected custom-select text-form w-50 text-capitalize">
                                <option selected="" value="">Đà Nẵng</option>
                                <option value="">TT Huế</option>
                            </select> -->
                            <button class="btn btn_spin btn-danger">Quay thử</button>
                        </div>
                    </div>
                </form>
            </div>
        </aside>
        <article class="mb-2">
                <?php
                    $time = [1 => '18:10', 2 => '17:10', 3 => '16:10'];
                    if ($oneItem->parent_id == 0) {
                        $displayed_time = strtotime($time[$oneItem->id]) <= strtotime(date('H:i')) ? date('d/m/Y', strtotime("+1day")) : date('d/m/Y');
                    } else {
                        $displayed_time = strtotime($time[$oneItem->parent_id]) <= strtotime(date('H:i')) ? date('d/m/Y', strtotime("+1day")) : date('d/m/Y');
                    }
                ?>
                
                <?php 
                    if ($oneItem->parent_id == 0) {
                        if ($oneItem->code == 'XSMB') {
                            $this->load->view(TEMPLATE_PATH . 'spin/quay_thu_XSMB', ['displayed_time' => $displayed_time]);
                        } else {
                            $this->load->view(TEMPLATE_PATH . 'spin/quay_thu_MT_MN', ['displayed_time' => $displayed_time]);
                        }
                    } else {
                        $this->load->view(TEMPLATE_PATH . 'spin/quay_thu_tinh', ['displayed_time' => $displayed_time]);
                    }
                ?>
                
        </article>
    </div>
</main>

<script>
    document.getElementById("selectProvince").onchange = function() {
        let url = document.getElementById("selectProvince").value;
        location.assign(url);
    };
</script>

<!-- <script>
    var DELAY = (function () {
        var queue = [];

        function processQueue() {
            if (queue.length > 0) {
                setTimeout(function () {
                    queue.shift().callBack();
                    processQueue();
                }, queue[0].delay);
            }
        }

        return function DELAY(delay, callBack) {
            queue.push({delay: delay, callBack: callBack});

            if (queue.length === 1) {
                processQueue();
            }
        };
    }());
    var GENERATE = {
        // tableResult: $('.boxSpin .table-result'),
        countRandom: 10,
        timeRandom: 100,
        timeDelayRandom: 2000,
        tagSpin: '<i class="icon-spinner spinning"></i>',
        classSpin: '.icon-spinner',
        tagLoadNumber: '<span class="loadNumber"></span>',
        classLoadNumber: '.loadNumber',
        random_number: function (length, selector, colLoto) {
            let number;
            let flag = 0;
            let run_random_number = setInterval(function () {
                if (flag < GENERATE.countRandom) {
                    flag++;
                    number = LOAD_NUMBER.random_number(length);
                    selector.html(GENERATE.tagLoadNumber).find(GENERATE.classLoadNumber).html(number);
                } else {
                    clearInterval(run_random_number);
                    number = number.replace(/(<([^>]+)>)/gi, "");
                    selector.html(number);
                    GENERATE.show_loto(colLoto, number.toString());
                }
            }, GENERATE.timeRandom);
        },
        show_loto: function (col, number) {
            let twoNumber = number.substr(number.length - 2);
            let head = twoNumber.substr(0, 1);
            let tail = twoNumber.substr(1, 1);

            let nthChildTr = parseInt(head) + 1;
            GENERATE.tableLoto.find('tbody > tr:nth-child(' + nthChildTr + ') > td:nth-child(' + col + ')').append('<span>' + tail + '</span>');
            if (GENERATE.tableLoto.hasClass('table-loto-single')) {
                nthChildTr = parseInt(tail) + 1;
                GENERATE.tableLoto.find('tbody tr:nth-child(' + nthChildTr + ') td:nth-child(4)').append('<span>' + head + '</span>');
            }
            GENERATE.done();
        },
        show_result_MB: function (childTr) {
            GENERATE.tableResult.find('tbody > tr' + childTr).each(function () {
                let trElement = $(this);
                trElement.find('td').each(function (iTd) {
                    $(this).find('span').each(function (iSpan) {
                        let spanElement = $(this);
                        let nc = spanElement.data('nc');
                        DELAY(GENERATE.timeDelayRandom, function (iTd) {
                            return function () {
                                let keyTD = iTd + 1;
                                return GENERATE.random_number(nc, spanElement, keyTD);
                            };
                        }(iTd, iSpan));
                    });
                });
            });
        },
        show_result_MN: function (childTr) {
            GENERATE.tableResult.find('tbody > tr' + childTr).each(function () {
                let trElement = $(this);
                trElement.find('td').each(function (iTd) {
                    $(this).find('.text-number').each(function (iSpan) {
                        let spanElement = $(this);
                        let nc = spanElement.data('nc');
                        DELAY(GENERATE.timeDelayRandom, function (iTd) {
                            return function () {
                                let keyTD = iTd + 1;
                                GENERATE.random_number(nc, spanElement, keyTD);
                            };
                        }(iTd, iSpan));
                    });

                });
            });
        },
        done: function () {
            let checkIconSpin = GENERATE.tableResult.find(GENERATE.classSpin);
            let checkRandom = GENERATE.tableResult.find(GENERATE.classLoadNumber);
            if (GENERATE.tableResult.hasClass('table-result-xsmb') && checkIconSpin.length === 3 && checkRandom.length === 0){
                $("button.btn_spin").prop('disabled',false);
                return true;
            } else if (checkIconSpin.length === 0 && checkRandom.length === 0){
                $("button.btn_spin").prop('disabled',false);
                return true;
            }
            return false;
        },
        init: function (tableResult) {
            if (GENERATE.tableResult !== undefined) return false;
            GENERATE.tableResult = tableResult;

            GENERATE.tableLoto = GENERATE.tableResult.closest('.boxResult').find('.table-loto');

            GENERATE.tableLoto.find('span').remove();
            GENERATE.tableResult.find('.text-number').html(GENERATE.tagSpin);
            if (GENERATE.tableResult.hasClass('table-result-xsmb')){
                GENERATE.show_result_MB(':nth-child(n+3)');
                GENERATE.show_result_MB(':nth-child(2)');
            } else if (GENERATE.tableResult.hasClass('table-result-province')){
                GENERATE.show_result_MN('');
            } else {
                GENERATE.show_result_MN(':not(:first-child)');
            }
        },
    };
    document.addEventListener("DOMContentLoaded", function() {
        if ($('.boxSpin:not(.notSpin) .table-result:has(.icon-spinner)').length > 1){
            let int = setInterval(function () {
                $('.boxSpin .table-result:has(.icon-spinner)').each(function (k, i) {
                    GENERATE.init($(i));
                });

                let check = GENERATE.done();
                if (check) {
                    GENERATE.tableResult = undefined;
                }
            }, 2000);
        } else if ($('.boxSpin:not(.notSpin) .table-result:has(.icon-spinner)').length === 1) {
            // trang quay thu
            GENERATE.init($('.boxSpin .table-result'));
            $('button.btn_spin').on('click', function () {
                GENERATE.tableResult = undefined;
                $(this).attr('disabled', true);
                GENERATE.init($('.boxSpin .table-result'));
            }).trigger('click');
        }
    });
</script>

<script>
    const AJAX_RESULT = {
        tableLoto: null,
        tagSpin: '<i class="icon-spinner spinning"></i>',
        classSpin: '.icon-spinner',
        tagLoadNumber: '<span class="loadNumber"></span>',
        classLoadNumber: '.loadNumber',

        load_loto: function (col, number) {
            let twoNumber = number.substr(number.length - 2);
            let head = twoNumber.substr(0,1);
            let tail = twoNumber.substr(1,1);

            let nthChildTr = parseInt(head) + 1;
            if(isNaN(nthChildTr) == false){
                AJAX_RESULT.tableLoto.find('tbody > tr:nth-child('+nthChildTr+') > td:nth-child('+col+')').append('<span>'+tail+'</span>');
            }
            if (AJAX_RESULT.tableLoto.hasClass('loto-single')) {
                if (tail >= 0){
                    let nthChildTailTr = parseInt(tail) + 1;
                    col = 4;
                    AJAX_RESULT.tableLoto.find('tbody > tr:nth-child(' + nthChildTailTr + ') > td:nth-child(' + col + ')').append('<span>' + head + '</span>');
                }
            }
        },
        clear_loto: function () {
            AJAX_RESULT.tableLoto.find('tbody > tr > td > span').remove();
        },
        socket_live: function () {
            let d = new Date();
            let n = d.getHours();
            let keyStorage  = 'xslive';
            if (n >= 16 && n < 19) {
            //if (1) {
                $.ajax({
                    url: base_url + 'result/ajax_live',
                    data: null,
                    type: 'post',
                    dataType: 'json',
                    success: function (res) {
                        AJAX_RESULT.push(res);
                    }
                });

                LOAD_NUMBER.check_load_number();
                NOTI_LIVE.loadNoti();
                let socket_sv = ['https://live1.xosoplus.com', 'https://live1.xosoplus.com'];

                /*socket chạy ngay*/
                let socket = io(socket_sv[Math.floor(Math.random() * socket_sv.length)],{secure:true});

                /*gọi đến storage trong khi chờ socket*/
                let resCache = localStorage.getItem(keyStorage);
                if (resCache) {
                    resCache = JSON.parse(resCache);
                    console.log('rescache');
                    AJAX_RESULT.push(resCache);
                }

                /*chờ socket trả dl*/
                socket.on('data',(res)=>{
                    /*reload khi socket đc kết nối*/
                    let keyReload = 'reloaded' + d.getHours();
                    let reloaded = sessionStorage.getItem(keyReload);
                    if (!reloaded) {
                        sessionStorage.setItem(keyReload, '1');
                        location.reload();
                    }

                    console.log('socket');
                    AJAX_RESULT.push(res);
                    localStorage.setItem(keyStorage,JSON.stringify(res));
                });
            } else {
                localStorage.removeItem(keyStorage);
                //localStorage.clear();
            }
        },
        push: function(res){
            let d = new Date();
            let m = 0;
            let n = d.getHours();
            let mi= d.getMinutes();
            if (mi >= 13){
                // if (0){
                let checkLoading = AJAX_RESULT.tableResult.find(AJAX_RESULT.classSpin);
                let checkLoadingRand = AJAX_RESULT.tableResult.find(AJAX_RESULT.classLoadNumber);
                if (checkLoading.length > 0 || checkLoadingRand.length > 0) {
                    let code = AJAX_RESULT.tableResult.data('code');
                    if (code !== '') {
                        AJAX_RESULT.clear_loto();
                        if ($.inArray(code, ['XSMN', 'XSMT']) === -1) {
                            if (res[code] !== undefined) {
                                let data_result = JSON.parse(res[code][0].data_result);
                                $.each(data_result,function (tr, valTr) {
                                    tr = tr+1;
                                    $.each(valTr,function (keyNumber, number) {
                                        keyNumber = keyNumber + 1;
                                        let elNumber = AJAX_RESULT.tableResult.find('tbody > tr:nth-child('+tr+') > td span.text-number:nth-child('+keyNumber+')');
                                        if(number.length > 0){
                                            elNumber.text(number);
                                            AJAX_RESULT.load_loto(2, number);
                                            m = 0;
                                        } else {
                                            let checkEmpty = AJAX_RESULT.tableResult.find(AJAX_RESULT.classLoadNumber);
                                            if (m == 0){
                                                if (code == 'XSMB'){
                                                    if (checkLoading.length <= 4){
                                                        if (tr == 2 && checkEmpty.length == 0){
                                                            elNumber.html(AJAX_RESULT.tagLoadNumber);
                                                            m =1;
                                                        }
                                                    }else {
                                                        if (tr > 2 && checkEmpty.length == 0){
                                                            elNumber.html(AJAX_RESULT.tagLoadNumber);
                                                            m =1;
                                                        }
                                                    }
                                                }else {
                                                    if (checkEmpty.length == 0){
                                                        elNumber.html(AJAX_RESULT.tagLoadNumber);
                                                        m =1;
                                                    }
                                                }
                                            }
                                        }
                                    })
                                })
                            }
                        } else {
                            // load multi col
                            let td = 2;
                            if (res[code] !== undefined) {
                                $.each(res[code],function (code, oneResult) {
                                    let data_result = JSON.parse(oneResult[0].data_result);
                                    $.each(data_result,function (tr, valTr) {
                                        tr = tr+2;
                                        $.each(valTr, function (keyNumber, number) {
                                            keyNumber = keyNumber + 1;
                                            let elNumber = AJAX_RESULT.tableResult.find('tbody > tr:nth-child('+tr+') > td:nth-child('+td+') .text-number:nth-child('+keyNumber+')');
                                            if(number.length > 0){
                                                elNumber.text(number);
                                                AJAX_RESULT.load_loto(td, number);
                                            } else {
                                                let firstTD = AJAX_RESULT.tableResult.find('td:nth-child('+td+') .text-number > span');
                                                if (firstTD.length === 0){
                                                    elNumber.html(AJAX_RESULT.tagLoadNumber);
                                                }
                                            }
                                        })
                                    });
                                    td++;
                                })
                            }
                        }
                    }
                }
            }

            /*tat thong bao khi quay xong*/
            let hour = {18:"XSMB",17:"XSMT",16:"XSMN"};
            let tmp = hour[n];
            let string = '';
            if (res[tmp] !== undefined) {
                if (n != 18){
                    $.each(res[tmp], function (k,i) {
                        string += i[0]['data_result'];
                    });
                    let nnn = string.search('\\"\\"');
                    if (nnn == -1){
                        NOTI_LIVE.hide();
                    } else NOTI_LIVE.loadNoti();
                } else {
                    string += res[tmp][0]['data_result'];
                    let nnn = string.search('""');
                    if (nnn == -1){
                        NOTI_LIVE.hide();
                    } else NOTI_LIVE.loadNoti();
                }
            }
        },

        init: function (tableResult) {
            if (AJAX_RESULT.tableResult !== undefined) return false;
            AJAX_RESULT.tableResult = tableResult;
            AJAX_RESULT.tableLoto = AJAX_RESULT.tableResult.closest('.boxResult').find('.table-loto');
            this.socket_live();
        }
    };

    const LOAD_NUMBER = {
        random_number: function (length) {
            let list_number = '';
            for (let i = 1; i <= length; i++){
                let rand = Math.floor(Math.random() * 10);
                list_number += '<i>'+rand+'</i>';
            }
            return list_number;
        },
        format_number: function (number) {
            let string      = number.replace(/<\/i>/g, "");
            string          = string.replace(/<i class="ghi">/g, "");
            string          = string.replace(/<i class="do">/g, "");
            return string;
        },
        load_number: function () {
            setInterval(function () {
                let checkEmpty = AJAX_RESULT.tableResult.find(AJAX_RESULT.classLoadNumber);
                if (checkEmpty.length > 0){
                    checkEmpty.each(function () {
                        let max = $(this).parent().data("nc");
                        let num = LOAD_NUMBER.random_number(max);
                        $(this).html(num);
                    });
                }
            }, 100);
        },
        check_time: function () {
            let tableResult = AJAX_RESULT.tableResult;

            // không còn icon chờ nữa thì return
            if (tableResult.find(AJAX_RESULT.classSpin).length === 0) return;

            // thêm class loadNumber
            if (tableResult.hasClass('table-province')) {
                // kqxs tỉnh
                let countLoadNumber = tableResult.find(AJAX_RESULT.classLoadNumber);
                if (countLoadNumber.length === 0){
                    tableResult.find('.text-number:first').html(AJAX_RESULT.tagLoadNumber);
                }
            } else if (tableResult.hasClass('table-xsmb')) {
                // kqxs xsmb
                let countLoadNumber = tableResult.find(AJAX_RESULT.classLoadNumber);
                let countSpin = tableResult.find(AJAX_RESULT.classSpin);
                if (countLoadNumber.length === 0){
                    if (countSpin.length > 4){
                        tableResult.find('tr:nth-child(n+3) .text-number:first').html(AJAX_RESULT.tagLoadNumber);
                    } else {
                        tableResult.find('tr:nth-child(2) .text-number').html(AJAX_RESULT.tagLoadNumber);
                    }
                }
            } else {
                // kqxs miền trung, nam
                for (let td=2;td<=5;td++) {
                    let countLoadNumber = tableResult.find('td:nth-child('+td+') .text-number:has(' + AJAX_RESULT.classLoadNumber + ')');
                    if (countLoadNumber.length === 0) {
                        tableResult.find('td:nth-child('+td+') .text-number:has(' + AJAX_RESULT.classSpin + '):first').html(AJAX_RESULT.tagLoadNumber);
                    }
                }
            }
        },
        check_load_number: function () {
            let d = new Date();
            let mi= d.getMinutes();
            if (mi <= 13) {
                let second = 0;
                let SInt_cln    =   setInterval(function () {
                    second = second + 1;
                    d = new Date();
                    mi = d.getMinutes();
                    if (mi === 13) {
                        LOAD_NUMBER.check_time();
                        clearInterval(SInt_cln);
                    }
                },1000);
            } else {
                LOAD_NUMBER.check_time();
            }
        }
    };

    const NOTI_LIVE = {
        timeEndCount: 13,
        timeEndLive: 35,
        loadNoti: function(){
            return;
            let date = new Date();
            let h = date.getHours();
            let m = date.getMinutes();
            let url = window.location.pathname;

            let notUrl = ['/so-ket-qua-mien-nam.html', '/so-ket-qua-mien-trung.html', '/so-ket-qua-mien-bac.html'];
            if ($("#notiLive").length == 0 && ($("table .icon-spinner").length == 0 || url.substring(0, 9) == '/quay-thu') && m <= this.timeEndLive) {
                if (url !== notUrl[h-16]){
                    $(".main").prepend("<div id='notiLive'></div>");
                    $("#notiLive").load(base_url + "application/views/public/default/_block/_live.html");
                }
            }
            if ($("table .icon-spinner").length == 0 && $(".loadNumber").length == 1) {
                $('#notiLive').hide();
            }
        },
        hide: function () {
            return;
            if ($("#notiLive").length == 0){
                let intval = setInterval(function (){
                    if ($("#notiLive").length > 0){
                        $("#notiLive").remove();
                        clearInterval(intval);
                    }
                },500)
            } else $("#notiLive").remove();
        }
    };
    (function($){
        "use strict";
        $(document).ready(function() {
            AJAX_RESULT.init($('.boxResult:not(.boxSpin) .table-result:has(.icon-spinner)'));
            LOAD_NUMBER.load_number();

            setInterval(function () {
                let today = new Date();
                let time = today.getHours() + ":" + today.getMinutes();

                if(time === "16:0" || time === "17:0" || time === "18:0") {
                    location.reload();
                }
            }, 1000*45);
        });
    })(jQuery);
</script> -->