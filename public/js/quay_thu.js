function random_number(length) {
    let list_number = '';
    for (let i = 1; i <= length; i++){
        let rand = Math.floor(Math.random() * 10);
        list_number += '<b><font color="#ff3020">'+rand+'</font></b>';
    }
    return list_number;
}

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
var SPIN = {
    // tableResult: $('.boxSpin .table-result'),
    countRandom: 10,
    timeRandom: 100,
    timeDelayRandom: 2000,
    tagSpin: '<i class="fas fa-spinner fa-pulse"></i>',
    classSpin: '.fa-spinner',
    tagLoadNumber: '<span class="loadNumber"></span>',
    classLoadNumber: '.loadNumber',
    random_number: function (length, selector, colLoto) {
        if (!SPIN.tableResult)
            return;
        let number;
        let flag = 0;
        let run_random_number = setInterval(function () {
            if (flag < SPIN.countRandom) {
                flag++;
                number = random_number(length);
                selector.html(SPIN.tagLoadNumber).find(SPIN.classLoadNumber).html(number);
            } else {
                clearInterval(run_random_number);
                number = number.replace(/(<([^>]+)>)/gi, "");
                selector.html(number);
                SPIN.show_loto(colLoto, number.toString());
            }
        }, SPIN.timeRandom);
    },
    show_loto: function (col, number) {
        let twoNumber = number.substr(number.length - 2);
        let head = twoNumber.substr(0, 1);
        let tail = twoNumber.substr(1, 1);

        let nthChildTr = parseInt(head) + 1;
        SPIN.tableLoto.find('tbody > tr:nth-child(' + nthChildTr + ') > td:nth-child(' + col + ')').append('<span>' + tail + '</span>');
        if (SPIN.tableLoto.hasClass('table-loto-single')) {
            nthChildTr = parseInt(tail) + 1;
            SPIN.tableLoto.find('tbody tr:nth-child(' + nthChildTr + ') td:nth-child(4)').append('<span>' + head + '</span>');
        }
        SPIN.done();
    },
    show_result_MB: function (childTr) {
        SPIN.tableResult.find('tbody > tr' + childTr).each(function () {
            let trElement = $(this);
            trElement.find('td').each(function (iTd) {
                $(this).find('span').each(function (iSpan) {
                    let spanElement = $(this);
                    let nc = spanElement.data('nc');
                    DELAY(SPIN.timeDelayRandom, function (iTd) {
                        return function () {
                            let keyTD = iTd + 1;
                            return SPIN.random_number(nc, spanElement, keyTD);
                        };
                    }(iTd, iSpan));
                });
            });
        });
    },
    show_result_MN: function (childTr) {
        SPIN.tableResult.find('tbody > tr' + childTr).each(function () {
            let trElement = $(this);
            trElement.find('td').each(function (iTd) {
                $(this).find('.text-number').each(function (iSpan) {
                    let spanElement = $(this);
                    let nc = spanElement.data('nc');
                    DELAY(SPIN.timeDelayRandom, function (iTd) {
                        return function () {
                            let keyTD = iTd + 1;
                            SPIN.random_number(nc, spanElement, keyTD);
                        };
                    }(iTd, iSpan));
                });

            });
        });
    },
    done: function () {
        let checkIconSpin = SPIN.tableResult.find(SPIN.classSpin);
        let checkRandom = SPIN.tableResult.find(SPIN.classLoadNumber);
        if (SPIN.tableResult.hasClass('table-result-xsmb') && checkIconSpin.length === 3 && checkRandom.length === 0){
            SPIN.tableResult = null;
            $("button.btn_spin").prop('disabled',false);
            return true;
        } else if (checkIconSpin.length === 0 && checkRandom.length === 0){
            SPIN.tableResult = null;
            $("button.btn_spin").prop('disabled',false);
            return true;
        }
        return false;
    },
    init: function (tableResult) {
        if (SPIN.tableResult !== undefined) return false;
        SPIN.tableResult = tableResult;

        SPIN.tableLoto = SPIN.tableResult.closest('article').find('.table-loto');

        SPIN.tableLoto.find('span').remove();
        SPIN.tableResult.find('.text-number').html(SPIN.tagSpin);
        if (SPIN.tableResult.hasClass('table-result-xsmb')){
            SPIN.show_result_MB(':nth-child(n+3)');
            SPIN.show_result_MB(':nth-child(2)');
        } else if (SPIN.tableResult.hasClass('table-result-province')){
            SPIN.show_result_MN('');
        } else {
            SPIN.show_result_MN(':not(:first-child)');
        }
    },
};
document.addEventListener("DOMContentLoaded", function() {
    if ($('.boxSpin:not(.notSpin) .table-result:has('+SPIN.classSpin+')').length > 1){
        let int = setInterval(function () {
            $('.boxSpin .table-result:has('+SPIN.classSpin+')').each(function (k, i) {
                SPIN.init($(i));
            });

            let check = SPIN.done();
            if (check) {
                SPIN.tableResult = undefined;
            }
        }, 2000);
    } else if ($('.boxSpin:not(.notSpin) .table-result:has('+SPIN.classSpin+')').length === 1) {
        // trang quay thu
        SPIN.init($('.boxSpin .table-result'));
        $('button.btn_spin').on('click', function () {
            SPIN.tableResult = undefined;
            $(this).attr('disabled', true);
            SPIN.init($('.boxSpin .table-result'));
        }).trigger('click');
    }
});