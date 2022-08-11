const filterTable = (function () {
    const evtClickLengthNumber = () => {
        $(document).on('click', "table > tfoot [type='radio']", function () {
            let table = $(this).closest('table'),
                value = $(this).val();
            table.find('.active').removeClass('active');
            filterNumberLength(table, value);
        });
    };
    const filterNumberLength = (table, value) => {
        table.find('tr > td > span.text-number').each(function (index, ele) {
            let element = $(ele);
            let text = element.text().trim();
            let html;
            if (value == 0) {
                html = text;
            } else {
                let cut = text.substring(0, text.length - value);
                html = text.replace(cut, '<span class="d-none">' + cut + '</span>');
            }
            element.html(html);
        });
    };
    const evtClickNumber = () => {
        let current;
        $(document).on('click', '.check-number>ul>li', function () {
            let li = $(this);
            filterNumber(li, current);
        });
    };
    const filterNumber = (li, current) => {
        let table = li.closest('table');
        let value = li.text();
        li.closest('td').find('[value="0"]').click();
        li.parent().find('.active').removeClass('active');
        if (current !== value) {
            current = li.text();
            li.addClass('active');
            table.find('tr>td>span').each(function (index, ele) {
                let element = $(ele);
                let text = element.text().trim();
                let tail = text.slice(-2);
                let head = text.replace(tail, '');
                let regex = new RegExp(value);
                let html;
                if (regex.test(tail)) {
                    html = head + '<number class="numberTail">' + tail + '</number>';
                } else {
                    html = text;
                }
                element.html(html);
            });
        } else {
            li.removeClass('active');
            table.find('tr>td>span').each(function (index, ele) {
                let element = $(ele);
                element.html(element.text());
                current = null;
            });
        }
    };
    return {
        init: function () {
            evtClickLengthNumber();
            evtClickNumber();
        },
    };
})();
let GUI = {
    social_share: function () {
        if ($('.social-share').length > 0) {
            $('.social-share').jsSocials({
                shares: ['twitter', 'facebook', 'email'],
            });
        }
    },
    rate: function () {
        let url = $('.detail-post').attr('data-url');
        $('.rateit').bind('rated', function (event, value) {
            event.preventDefault();
            let ri = $(this);
            $.ajax({
                url: base_url + 'reviews/ajax_vote',
                data: {
                    url: url,
                    rate: value,
                },
                beforeSend: function () {
                    ri.rateit('readonly', true);
                },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    if (data.vote) {
                        ri.rateit(data.vote.avg);
                    }

                    if (data.type == 'success')
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    if (data.type == 'warning') ri.rateit('readonly', true);
                    else ri.rateit('readonly', false);

                    $('.toast-body').html(
                        '<span style="font-weight: 600; font-size: 13px">' + data.message + '</span>'
                    );
                    $('.toast').toast('show');
                },
                error: function (jxhr, msg, err) {
                    console.log(jxhr);
                    console.log(msg);
                    console.error(err);
                },
            });
        });
    },
    loadMorePost: function () {
        if ($('button.btnLoadMore').length > 0) {
            $(document).on('click', 'button.btnLoadMore', function () {
                let el = $(this);
                let url = el.data('url');
                let page = parseInt(el.attr('data-page'));
                el.attr('disable', true);
                $.ajax({
                    url: url + '/' + page,
                    type: 'POST',
                    beforeSend: function () {
                        el.append('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function (html) {
                        if (html !== 'empty') {
                            page++;
                            let contentPage = $(html).find('#ajax_content').html();
                            $('#ajax_content').append(contentPage);
                            el.attr('data-page', page);
                            el.children('i').remove();
                        } else {
                            $(el).html('Hết dữ liệu !');
                        }
                        el.attr('disable', false);
                    },

                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    },
                });
            });
        }
    },
    renderBanner: function () {
        setTimeout(function () {
            /* get data banner */
            var data = JSON.parse(JSON.stringify(data_banner));
            /* dom banners */
            var banners = $('.bannerElement');
            if (banners.length > 0 && data.length > 0) {
                banners.each(function () {
                    let location = $(this).data('location');
                    let device = $(this).data('device');

                    /* filter data banner and location */
                    var filteredBanner = $(data).filter(function (n) {
                        return data[n].location == location && data[n].is_device == device;
                    });

                    /* sort after filter */
                    if (filteredBanner.length > 1) {
                        filteredBanner.sort(function (a, b) {
                            return a.sort - b.sort;
                        });
                    }

                    /* append banner for each location */
                    for (let $k = 0; $k < filteredBanner.length; $k++) {
                        if (location == filteredBanner[$k].location && device == filteredBanner[$k].is_device) {
                            let info = JSON.parse(filteredBanner[$k].data_info);
                            let link =
                                `<a href="` +
                                info.link +
                                `" class="d-block ` +
                                `" rel="nofollow" target="_blank" title="` +
                                info.title +
                                `"><img class="img-fluid" src="` +
                                media_url +
                                filteredBanner[$k].thumbnail +
                                `" alt="` +
                                info.alt +
                                `" title="` +
                                info.title +
                                `"></a>`;
                            $(this).append(link);
                        }
                    }
                });
            }
        }, 2000);
    },
    Popup: function () {
        setTimeout(function () {
            // banner popup
            let banner = $('#ads_popup');
            let keyPopup = 'checkPopup';

            if (!sessionStorage.getItem(keyPopup) && $('#ads_popup .modal-body img').length > 0) {
                sessionStorage.setItem(keyPopup, 1);
                banner.modal('show');
            } else {
                console.log('ton tai keyPopup');
            }
            // 30 phut
            setInterval(function () {
                if (sessionStorage.getItem(keyPopup)) {
                    sessionStorage.removeItem(keyPopup);
                }
                console.log('xoa keyPopup');
            }, 18000000);
        }, 3000);
    },
    mMenu: function () {
        let menu = new MmenuLight(document.querySelector('#menu-mobile'), {
            title: 'Xosotv.net',
            slidingSubmenus: false,
        });
        menu.enable('(max-width: 991px)');
        menu.offcanvas();

        document.querySelector('a[href="#menu-mobile"]').addEventListener('click', (e) => {
            menu.open();
            e.preventDefault();
            e.stopPropagation();
        });
    },
    backTop: function () {
        let back = $('.back-top');
        back.css('cursor', 'pointer');
        back.on('click', function () {
            document.documentElement.scrollTop = 0;
        });
        document.onscroll = function (e) {
            if (document.documentElement.scrollTop > 150) {
                back.removeClass('d-none');
            } else {
                back.addClass('d-none');
            }
        };
    },
    navActive: function () {
        $('ul>li a[href="' + window.location.pathname + '"]')
            .parent()
            .addClass('active')
            .parent()
            .parent()
            .addClass('active');
        $('.submenu2-bg>li a[href="' + window.location.origin + window.location.pathname + '"]')
            .parent()
            .addClass('active');
    },
    countGameAjax: function () {
        let ele = $('.count_ajax');
        if (ele.length > 0) {
            count = setInterval(() => {
                let eleTime = ele.find('.time-end > span');
                let time = eleTime.text().match(/\d+/)[0];
                let count = time - 1 < 0 ? 0 : time - 1;
                eleTime.text(count + ' giây');
                if (time == 0) {
                    ele.find('.game-detail').html(
                        '<p style=" height: 100%; display: flex; padding: 34px 0; font-weight: 600; color: blue; ">Đang quay giải </p>'
                    );
                    eleTime.text('Đã ngừng nhận cược');
                    setTimeout(() => {
                        $.get(window.location.href, function (html, status) {
                            let contentGame = $(html).find('.count_ajax').html();
                            ele.html(contentGame);
                            let contentPage = $(html).find('#ajax_content').html();
                            $('#ajax_content').html(contentPage);
                        });
                    }, 10000);
                }
            }, 1000);
        }
    },
    datepicker: function () {
        
        $.datepicker.regional["vi-VN"] = {
            closeText: "Đóng",
            prevText: "Trước",
            nextText: "Sau",
            currentText: "Hôm nay",
            monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
            monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
            dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
            dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
            dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
            weekHeader: "Tuần",
            dateFormat: "dd/mm/yy",
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ""
        };
        $.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
        $('#datepicker,#frm_dove_ngay,#txtngay, #tungay, #denngay, #ngay').datepicker({
            maxDate: new Date(),
            dateFormat: 'dd-mm-yy',
            onSelect: function (a, b) {
                console.log(b.id);
            },
        });
    },
    openLinkGame: function () {
        $('.count_ajax').on('click', function (e) {
            if ($(e.target).attr('class') === 'bet') {
                window.open('http://gg.gg/xosok8', '_blank');
            }
        });
    },
    watchResult: function () {
        $('.count_ajax').on('click', function (e) {
            if ($(e.target).attr('class') === 'watch-result') {
                $.ajax({
                    url: base_url + 'game' + window.location.pathname,
                    // type: 'POST',
                    // dataType: 'JSON',
                    success: function (data) {
                        $('#game-modal .modal-body p').html('<pre>' + data + '</pre>');
                    },
                    error: function (jxhr, msg, err) {
                        console.log(jxhr);
                        console.log(msg);
                        console.error(err);
                    },
                });
            }
        });
    },
    init: function () {
        this.watchResult();
        this.openLinkGame();
        this.backTop();
        this.navActive();
        this.countGameAjax();
        GUI.rate();
        GUI.datepicker();

        // GUI.social_share();
        GUI.loadMorePost();
        GUI.mMenu();
        GUI.renderBanner();
        // GUI.Popup();
    },
};
$(document).ready(function () {
    GUI.init();
    filterTable.init();
});
