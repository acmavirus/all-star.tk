/*!
 * Social Share Kit v1.0.14 (http://socialsharekit.com)
 * Copyright 2015 Social Share Kit / Kaspars Sprogis.
 * @Licensed under Creative Commons Attribution-NonCommercial 3.0 license:
 * https://github.com/darklow/social-share-kit/blob/master/LICENSE
 * ---
 */
var SocialShareKit = function() {
    function e(e) {
        return b(e).share()
    }

    function t(e) {
        "loading" != document.readyState ? e() : document.addEventListener ? document.addEventListener("DOMContentLoaded", e) : document.attachEvent("onreadystatechange", function() {
            "loading" != document.readyState && e()
        })
    }

    function n(e) {
        return document.querySelectorAll(e)
    }

    function o(e, t) {
        for (var n = 0; n < e.length; n++) t(e[n], n)
    }

    function r(e, t, n) {
        e.addEventListener ? e.addEventListener(t, n) : e.attachEvent("on" + t, function() {
            n.call(e)
        })
    }

    function i(e) {
        return e.className.match(g)
    }

    function a(e) {
        var t = e || window.event;
        return t.preventDefault ? t.preventDefault() : (t.returnValue = !1, t.cancelBubble = !0), t.currentTarget || t.srcElement
    }

    function c(e, t, n) {
        var o, r, i, a;
        return t && n ? (r = document.documentElement.clientWidth / 2 - t / 2, i = (document.documentElement.clientHeight - n) / 2, a = "status=1,resizable=yes,width=" + t + ",height=" + n + ",top=" + i + ",left=" + r, o = window.open(e, "", a)) : o = window.open(e), o.focus(), o
    }

    function s(e, t, n) {
        var o, r = f(e, t, n),
            i = u(e, t, n, r),
            a = "undefined" != typeof r.title ? r.title : d(t),
            c = "undefined" != typeof r.text ? r.text : l(t),
            s = r.image ? r.image : p("og:image"),
            h = "undefined" != typeof r.via ? r.via : p("twitter:site"),
            m = {
                shareUrl: i,
                title: a,
                text: c,
                image: s,
                via: h,
                options: e,
                shareUrlEncoded: function() {
                    return encodeURIComponent(this.shareUrl)
                }
            };
        switch (t) {
            case "facebook":
                o = "https://www.facebook.com/share.php?u=" + m.shareUrlEncoded();
                break;
            case "twitter":
                o = "https://twitter.com/intent/tweet?url=" + m.shareUrlEncoded() + "&text=" + encodeURIComponent(a + (c && a ? " - " : "") + c), h && (o += "&via=" + h.replace("@", ""));
                break;
            case "google-plus":
                o = "https://plus.google.com/share?url=" + m.shareUrlEncoded();
                break;
            case "pinterest":
                o = "https://pinterest.com/pin/create/button/?url=" + m.shareUrlEncoded() + "&description=" + encodeURIComponent(c), s && (o += "&media=" + encodeURIComponent(s));
                break;
            case "tumblr":
                o = "https://www.tumblr.com/share/link?url=" + m.shareUrlEncoded() + "&name=" + encodeURIComponent(a) + "&description=" + encodeURIComponent(c);
                break;
            case "linkedin":
                o = "https://www.linkedin.com/shareArticle?mini=true&url=" + m.shareUrlEncoded() + "&title=" + encodeURIComponent(a) + "&summary=" + encodeURIComponent(c);
                break;
            case "vk":
                o = "https://vkontakte.ru/share.php?url=" + m.shareUrlEncoded();
                break;
            case "buffer":
                o = "https://buffer.com/add?source=button&url=" + m.shareUrlEncoded() + "&text=" + encodeURIComponent(c);
                break;
            case "email":
                o = "mailto:?subject=" + encodeURIComponent(a) + "&body=" + encodeURIComponent(a + "\n" + i + "\n\n" + c + "\n")
        }
        return m.networkUrl = o, e.onBeforeOpen && e.onBeforeOpen(n, t, m), m.networkUrl
    }

    function u(e, t, n, o) {
        return o = o || f(e, t, n), o.url || window.location.href
    }

    function d(e) {
        var t;
        return "twitter" == e && (t = p("twitter:title")), t || document.title
    }

    function l(e) {
        var t;
        return "twitter" == e && (t = p("twitter:description")), t || p("description")
    }

    function p(e, t) {
        var o, r = n("meta[" + (t ? t : 0 === e.indexOf("og:") ? "property" : "name") + '="' + e + '"]');
        return r.length && (o = r[0].getAttribute("content") || ""), o || ""
    }

    function f(e, t, n) {
        var o, r, i, a, c = ["url", "title", "text", "image"],
            s = {},
            u = n.parentNode;
        "twitter" == t && c.push("via");
        for (a in c) r = c[a], i = "data-" + r, o = n.getAttribute(i) || u.getAttribute(i) || (e[t] && "undefined" != typeof e[t][r] ? e[t][r] : e[r]), "undefined" != typeof o && (s[r] = o);
        return s
    }

    function h(e, t) {
        var n = document.createElement("div");
        n.innerHTML = t, n.className = "ssk-num", e.appendChild(n)
    }

    function m(e, t, n, o) {
        var r, i, a, c = encodeURIComponent(t);
        switch (e) {
            case "facebook":
                r = "https://graph.facebook.com/?id=" + c, i = function(e) {
                    return o(e.share ? e.share.share_count : 0)
                };
                break;
            case "twitter":
                n && n.twitter && n.twitter.countCallback && n.twitter.countCallback(t, o);
                break;
            case "google-plus":
                return r = "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ", a = '[{"method":"pos.plusones.get","id":"p","params":{"id":"' + t + '","userId":"@viewer","groupId":"@self","nolog":true},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]', i = function(e) {
                    if (e = JSON.parse(e), e.length) return o(e[0].result.metadata.globalCounts.count)
                }, void w(r, i, a);
            case "linkedin":
                r = "https://www.linkedin.com/countserv/count/share?url=" + c, i = function(e) {
                    return o(e.count)
                };
                break;
            case "pinterest":
                r = "https://api.pinterest.com/v1/urls/count.json?url=" + c, i = function(e) {
                    return o(e.count)
                };
                break;
            case "vk":
                r = "https://vk.com/share.php?act=count&url=" + c, i = function(e) {
                    return o(e)
                };
                break;
            case "buffer":
                r = "https://api.bufferapp.com/1/links/shares.json?url=" + c, i = function(e) {
                    return o(e.shares)
                }
        }
        r && i && v(e, r, i, a)
    }

    function w(e, t, n) {
        var o = new XMLHttpRequest;
        o.onreadystatechange = function() {
            4 === this.readyState && this.status >= 200 && this.status < 400 && t(this.responseText)
        }, o.open("POST", e, !0), o.setRequestHeader("Content-Type", "application/json"), o.send(n)
    }

    function v(e, t, n) {
        var o = "cb_" + e + "_" + Math.round(1e5 * Math.random()),
            r = document.createElement("script");
        return window[o] = function(e) {
            try {
                delete window[o]
            } catch (e) {}
            document.body.removeChild(r), n(e)
        }, "vk" == e ? window.VK = {
            Share: {
                count: function(e, t) {
                    window[o](t)
                }
            }
        } : "google-plus" == e && (window.services = {
            gplus: {
                cb: window[o]
            }
        }), r.src = t + (t.indexOf("?") >= 0 ? "&" : "?") + "callback=" + o, document.body.appendChild(r), !0
    }
    var b, k, g = /(twitter|facebook|google-plus|pinterest|tumblr|vk|linkedin|buffer|email)/,
        y = "*|*";
    return k = function(e) {
        var t = e || {},
            o = t.selector || ".ssk";
        this.nodes = n(o), this.options = t
    }, k.prototype = {
        share: function() {
            function e(e) {
                var t, n = a(e),
                    o = i(n),
                    r = o[0];
                if (o && (t = s(l, r, n))) {
                    if (window.twttr && n.getAttribute("href").indexOf("twitter.com/intent/") !== -1) return void n.setAttribute("href", t);
                    if ("email" !== r) {
                        var u, d;
                        "buffer" === r ? (u = 800, d = 680) : (u = 575, d = 400);
                        var p = c(t, u, d);
                        if (l.onOpen && l.onOpen(n, r, t, p), l.onClose) var f = window.setInterval(function() {
                            p.closed !== !1 && (window.clearInterval(f), l.onClose(n, r, t, p))
                        }, 250)
                    } else document.location = t
                }
            }

            function n() {
                var e, t;
                for (e in p) t = e.split(y),
                    function(e) {
                        m(t[0], t[1], l, function(t) {
                            for (var n in e) h(e[n], t)
                        })
                    }(p[e])
            }
            var d = this.nodes,
                l = this.options,
                p = {},
                f = function() {
                    d.length && (o(d, function(t) {
                        var n, o = i(t);
                        o && (t.getAttribute("data-ssk-ready") || (t.setAttribute("data-ssk-ready", !0), r(t, "click", e), t.parentNode.className.indexOf("ssk-count") !== -1 && (o = o[0], n = o + y + u(l, o, t), n in p || (p[n] = []), p[n].push(t))))
                    }), n())
                };
            return l.forceInit === !0 ? f() : t(f), this.nodes
        }
    }, b = function(e) {
        return new k(e)
    }, {
        init: e
    }
}();
window.SocialShareKit = SocialShareKit;
var UI = {
    socialShare: function() {
        SocialShareKit.init({
            onBeforeOpen: function(targetElement, network, paramsObj) {
                console.log(arguments);
            },
            onOpen: function(targetElement, network, networkUrl, popupWindow) {
                console.log(arguments);
            },
            onClose: function(targetElement, network, networkUrl, popupWindow) {
                console.log(arguments);
            }
        });
    },
    init: function() {
        UI.socialShare();
    },
};