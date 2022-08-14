const loading = '<div class="_2iwo" data-testid="fbfeed_placeholder_story"> <div class="_2iwq"> <div class="_2iwr"></div><div class="_2iws"></div><div class="_2iwt"></div><div class="_2iwu"></div><div class="_2iwv"></div><div class="_2iww"></div><div class="_2iwx"></div><div class="_2iwy"></div><div class="_2iwz"></div><div class="_2iw-"></div><div class="_2iw_"></div><div class="_2ix0"></div></div></div>';

const SYSTEM = {
    data_result: function (url, request, type, beforeSend) {
        let data = [];
        $.ajax({
            type: 'POST',
            url: url,
            data: request,
            dataType: type,
            async: false,
            beforeSend: function () {
                $(beforeSend).html(loading);
            },
            success: function (content) {
                data = $(content).find(beforeSend).html();
            }
        });
        return data;
    },
    data_request: function (url, request, id) {
        $.ajax({
            type: 'POST',
            url: url,
            data: request,
            dataType: 'html',
            beforeSend: function () {
                $('#' + id).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (content) {
                $('#' + id).html(content);
            }
        });
    },
    page_loading: function (url, request, type, beforeSend) {
        $.ajax({
            type: 'POST',
            url: url,
            data: request,
            dataType: type,
            beforeSend: function () {
                $(beforeSend).html(loading);
            },
            success: function (content) {
                let body = $(content).find(beforeSend).html();
                $("#content").html(body);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
};
const MENU = {
    active: function () {
        $('.menu ul.box li').find('a').click(function (e) {
            e.preventDefault();
            $(this).closest("ul").find("li").removeClass('active');
            $(this).parent().addClass('active');
            let page_url = base_url + $(this).data('href');
            let body = SYSTEM.page_loading(page_url, {}, 'HTML', '#content');
            let title = $(this).html();
            let urlPath = $(this).attr("href");
            document.title = title;
            window.history.pushState({
                "html": body,
                "pageTitle": title
            }, "", urlPath);
        });
    },
    init: function () {
        MENU.active();
    }
}
$(document).ready(function () {
    MENU.init();
});