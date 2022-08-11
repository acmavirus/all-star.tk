var pos1 = 0;
var pos2 = 0;
var flag = 0;
var classSelected = 'text-primary';

$(document).ready(function () {
    if ($('.jsSoiCau').length > 0) {
        if (typeof lifetime == 'undefined') {
            $('#kqcaumb').html('Hôm nay không có cầu!!!');
        } else {
            writeListCoupleValue();
            let inputCau = $('[name="so_cau"]');
            let minCau = lifetime[0];
            let maxCau = lifetime[lifetime.length - 1];
            inputCau.attr('min', minCau).val(minCau);
            inputCau.attr('max', maxCau);
            initTableCau(inputCau.val());
            $('button.btnFilterBachThu').click(function (e) {
                let so_cau = $('input[name="so_cau"]').val();
                initTableCau(so_cau);
                initListCau(so_cau, minCau);
            });
            $(document).on('click', '#btn-plus', function () {
                inputCau[0].stepUp($(this).data('value'));
            });
            $(document).on('click', '#btn-minus', function () {
                if (inputCau.val() - 1 >= inputCau.attr('min')) inputCau.val(inputCau.val() - 1);
            });
            setActive();
        }
    }
    $(document).on('click', '.clickLotoColor', function () {
        let num = $(this).data('number');
        clickLotoColor(num);
        resetColorListSoicau();
    });
});

function initListCau(val, min) {
    $('.check_bien_do').removeClass('d-none');
    for (i = val - 1; i >= min; i--) {
        $('[data-bien_do="' + i + '"]').addClass('d-none');
    }
}

function initTableCau(so_cau) {
    $('.block-number').addClass('d-none');
    if ($('[data-bien_do="' + so_cau + '"]').length > 0) {
        $('.block-number number').text(0);
        $('[data-bien_do="' + so_cau + '"] .item-tk-cau-icon').each(function (k, v) {
            let number = $(this).text();
            $('#count_' + number).closest('.block-number').removeClass('d-none');
            let countEl = $('#count_' + $(v).text());
            countEl.text(parseInt(countEl.text()) + 1);
        });
    } else {
        so_cau++;
        initTableCau(so_cau);
    }
}

function writeCau() {
    setTimeout("writeListCoupleValue();", 2000);
}

function writeListCoupleValue() {
    let strHtml;
    strHtml = writeCouplevalueAll();
    $("#kqcaumb").html(strHtml);
}

function writeCouplevalueAll() {
    let life = 0;
    let result = "<div class=\"list-tkcau\">";
    let lifetimelimit = 3;
    if (lifetime.length > 0) lifetimelimit = lifetime.length - 1;
    let tableNumb = $('.table-soi-cau table tr > td number');
    tableNumb.text(0);
    for (let idx = lifetimelimit; idx >= 0; idx--) {
        life = lifetime[idx];
        result = result + "<div class=\"list-tkcau check_bien_do\" data-bien_do='" + String(life) + "'><div class=\"rows\">+ Biên độ " + String(life) + " ngày:</div><div class=\"rows\">" + writeCouplevaluebylife(life) + "</div></div>";
        for (let index = lifetime.length - 1; index >= idx; index--) {
            if (lifetime[index] == life)
                idx = idx - 1;
        }
        idx++;
    }

    $('[data-toggle="tooltip"]').tooltip();
    return result;
}

function writeCouplevaluebylife(life) {
    let result = "";

    for (let idx = 0; idx < lifetime.length; idx++) {
        let index = ('0' + valuelt[idx]).substr(-2, 2);
        let countEl = $('#count_' + index);
        if (lifetime[idx] == life) {
            result = result + "<span class=\"bg-grey8 p-2 mt-2 fw-bold fs-16 me-2 d-inline-block item-tk-cau-icon soicau_mb\" onclick =\"setcolortoloto(this," + String(positionOne[idx]) + "," + String(positionTwo[idx]) + ");\">" + ('0' + valuelt[idx]).substr(-2, 2) + "</span>";
        }
        countEl.text(parseInt(countEl.text()) + 1);
        countEl.closest('.block-number').attr('onclick', 'setcolortoloto(this,' + String(positionOne[idx]) + ',' + String(positionTwo[idx]) + ')');
        countEl.closest('.block-number').attr('data-toggle', 'tooltip');
        countEl.closest('.block-number').attr('title', 'Vị trí tạo cầu: ' + String(positionOne[idx]) + ',' + String(positionTwo[idx]));
    }
    return result;
}

function setcolortoloto(obj, mpos1, mpos2) {
    if (flag !== 0) {
        flag = 2;
        pos1 = 0;
        pos2 = 0;
        resetTableResult();
    }

    clickLotoColor(mpos1);
    clickLotoColor(mpos2);
    resetColorListSoicau();
    resetColorTableSoicau();
    $(obj).removeClass("soicau_mb").addClass("caumbloto");
    setTimeout(clickScroll('ketqua'), 500);

}

function setFlag() {
    flag = flag + 1;
    if (flag == 3)
        flag = 0;
    if (flag == 0) {
        flag = flag + 1;
        resetColor();
    }
    return false;
}

function setloto(pos) {
    if (pos1 == 0)
        pos1 = pos;
    else if (pos2 == 0)
        pos2 = pos;
    else {
        pos1 = pos;
        pos2 = 0;
    }
    if (pos2 == pos1)
        pos1 = 0;
    return false;
}

function resetColor() {
    $("." + classSelected).removeClass().addClass("soicau_mb");
    $(".text-number .text-warning").removeClass().addClass("soicau_mb");
}

function scrollToElement(selector, callback) {
    var animation = { scrollTop: $(selector).offset().top - 80 };
    $('html,body').animate(animation, 'slow', 'swing', function () {
        if (typeof callback == 'function') {
            callback();
        }
        callback = null;
    });
}

function clickScroll(kq_id) {

    window.setTimeout(function () {
        scrollToElement("#" + kq_id);
    }, 1000);
}

function setActive() {
    $('.jsSoiCau .block-number, .jsSoiCau .item-tk-cau-icon').on('click', function () {
        $('.jsSoiCau .active').removeClass('active');
        $(this).addClass('active');
    });
}
function resetTableResult() {
    $('#ketqua').find('.text-primary, .text-warning').removeClass('text-primary').removeClass('text-warning');
}
function resetColorListSoicau() {
    $('#kqcaumb').find('.active').removeClass('active');
}
function resetColorTableSoicau() {
    $('.table-soicau').find('.active').removeClass('active');
}

if ($('#lich').length > 0) {
    $("#lich").datepicker({
        format: 'dd-mm-yyyy',
        endDate: '+0d',
        weekStart: 1,
        language: 'vi',
        templates: {
            leftArrow: '<i class="icon-cheveron-left"></i>',
            rightArrow: '<i class="icon-cheveron-right"></i>'
        }
    }).on('changeDate', function () {
        let code = $("#codeCalendar").data('code');
        let value = $('#lich').datepicker('getFormattedDate');
        window.location.href = base_url + code + '-ngay-' + value + '.html';
    });
}