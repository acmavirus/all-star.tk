<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if (!function_exists('site_admin_url')) {
    function site_admin_url($uri = '')
    {
        return BASE_ADMIN_URL . $uri;
    }
}

if (!function_exists('getUrlPage')) {
    function getUrlPage($optional)
    {
        $linkReturn = BASE_URL . "$optional->slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPost')) {
    function getUrlPost($oneItem)
    {
        $linkReturn = BASE_URL . "$oneItem->slug-p$oneItem->id";
        return $linkReturn;
    }
}

if (!function_exists('getUrlCategory')) {
    function getUrlCategory($oneItem)
    {
        $linkReturn = BASE_URL . "$oneItem->slug" . '.html';
        return $linkReturn;
    }
}

if (!function_exists('getUrlCategoryCode')) {
    function getUrlCategoryCode($oneItem)
    {
        $linkReturn = BASE_URL . strtolower($oneItem->code) . "-xo-so-" . "$oneItem->slug" . '.html';
        return $linkReturn;
    }
}

if (!function_exists('getUrlTournament')) {
    function getUrlTournament($oneItem)
    {
        $linkReturn = BASE_URL . "giai-dau/$oneItem->slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlTaxonomy')) {
    function getUrlTaxonomy($slug)
    {
        $linkReturn = BASE_URL . $slug;
        return $linkReturn;
    }
}

if (!function_exists('getUrlTag')) {
    function getUrlTag($optional)
    {
        $linkReturn = BASE_URL . "tags/$optional->slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlMatch')) {
    function getUrlMatch($optional)
    {
        $_this =& get_instance();
        if (is_object($optional)) {
            $optional = (array)$optional;
        }
        $id = $optional['match_id'];
        $slug = $optional['name_home'] . " vs " . $optional['name_away'];
        $slug = $_this->toSlug($slug);
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-l$id.html";
        if (!empty($optional['data_link'])) {
            $linkReturn .= "?server=0";
        }
        if (!empty($optional['data_link_wp'])) {
            $linkReturn .= "?server_link=0";
        }
        return $linkReturn;
    }
}

if (!function_exists('getUrlMatchNoLink')) {
    function getUrlMatchNoLink($optional)
    {
        $_this =& get_instance();
        if (is_object($optional)) {
            $optional = (array)$optional;
        }
        $id = $optional['match_id'];
        $slug = $optional['name_home'] . " vs " . $optional['name_away'];
        $slug = $_this->toSlug($slug);
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-l$id.html";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPlayer')) {
    function getUrlPlayer($optional, $index = 0)
    {
        if (is_object($optional)) {
            $optional = (array)$optional;
        }
        $id = $optional['match_id'];
        $linkReturn = BASE_URL;
        if (empty($index)) {
            $index = 0;
        }
        $linkReturn .= "x-" . md5(date('Y-m-d H')) . "-w$id-c$index";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPlayerOther')) {
    function getUrlPlayerOther($optional, $index = 0)
    {
        if (is_object($optional)) {
            $optional = (array)$optional;
        }
        $id = $optional['match_id'];
        $linkReturn = BASE_URL;
        if (empty($index)) {
            $index = 0;
        }
        $linkReturn .= "x_o-" . md5(date('Y-m-d H')) . "-w$id-c$index";
        return $linkReturn;
    }
}
if (!function_exists('getUrlPlayerVideo')) {
    function getUrlPlayerVideo($optional, $index = 0)
    {
        if (is_object($optional)) {
            $optional = (array)$optional;
        }
        $id = $optional['id'];
        $linkReturn = BASE_URL;
        $linkReturn .= "video-" . md5(date('Y-m-d H')) . "-l$id";
        return $linkReturn;
    }
}

if (!function_exists('cutString')) {
    function cutString($chuoi, $max)
    {
        $length_chuoi = strlen($chuoi);
        if ($length_chuoi <= $max) {
            return $chuoi;
        } else {
            return mb_substr($chuoi, 0, $max, 'UTF-8') . '...';
        }
    }
}

if (!function_exists('getUrlSearch')) {
    function getUrlSearch($keyword)
    {
        return BASE_URL . "tim-kiem?keyword=$keyword";
    }
}
if (!function_exists('getTableOfContent')) {
    function getTableOfContent($content)
    {
        // $content = strip_tags($content, '<center><img><h2><h3><h4><p><strong><br><table><th><tr><td>');
        // if (empty($content)) {
        //     return false;
        // }
        $_this =& get_instance();
        preg_match_all('/<h[2-6]*[^>]*>.*?<\/h[2-6]>/', $content, $headings);
        if (!empty($headings[0])) {
            $index_h2 = 0;
            $index_h3 = 0;
            $index_h4 = 0;
            $index_h5 = 0;
            $index_h6 = 0;

            $main_content = '';
            foreach ($headings[0] as $key => $heading) {
                $key = $key + 1;
                $title = strip_tags($heading);
                $slug = $_this->toSlug($title);
                if (preg_match('/\bh2\b/', $heading)) {
                    $replace_heading = str_replace('<h2', '<h2 id="' . $slug . '"', $heading);
                    $content = str_replace($heading, $replace_heading, $content);
                    $index_h2++;
                    $index_h3 = 0;
                    $main_content .= '<li class="toc-h2"><a href="#' . $slug . '" title="' . $title . '">' . $title . '</a></li>';
                } elseif (preg_match('/\bh3\b/', $heading)) {
                    $replace_heading = str_replace('<h3', '<h3 id="' . $slug . '"', $heading);
                    $content = str_replace($heading, $replace_heading, $content);
                    $index_h3++;
                    $index_h4 = 0;
                    $main_content .= '<li class="toc-h3"><a href="#' . $slug . '" title="' . $title . '">' . $title . '</a></li>';
                } elseif (preg_match('/\bh4\b/', $heading)) {
                    $replace_heading = str_replace('<h4', '<h4 id="' . $slug . '"', $heading);
                    $content = str_replace($heading, $replace_heading, $content);
                    $index_h4++;
                    $index_h5 = 0;
                    $main_content .= '<li class="toc-h4"><a href="#' . $slug . '" title="' . $title . '">' . $title . '</a></li>';
                } elseif (preg_match('/\bh5\b/', $heading)) {
                    $replace_heading = str_replace('<h5', '<h5 id="' . $slug . '"', $heading);
                    $content = str_replace($heading, $replace_heading, $content);
                    $index_h5++;
                    $index_h6 = 0;
                    $main_content .= '<li class="toc-h5"><a href="#' . $slug . '" title="' . $title . '">' . $title . '</a></li>';
                } elseif (preg_match('/\bh6\b/', $heading)) {
                    $replace_heading = str_replace('<h6', '<h6 id="' . $slug . '"', $heading);
                    $content = str_replace($heading, $replace_heading, $content);
                    $index_h6++;
                    $main_content .= '<li class="toc-h6"><a href="#' . $slug . '" title="' . $title . '">' . $title . '</a></li>';
                }
            }
            $toc = '
            <div class="border border-radius px-3 pt-3 mb-3  table-of-contents">
                <div class="mb-3 table-radius">
                      <div class="font-16 text-title font-weight-bold head-catalog">
                         NỘI DUNG CHÍNH
                      </div>
                    <ul class="catalog">
                        ' . $main_content . '
                    </ul>
                </div>
            </div>
        ' . $content . '
        ';
        } else {
            $toc = $content;
        }
        return $toc;
    }
}
if (!function_exists('getUrlWeekday')) {
    function getUrlWeekday($oneParent, $weekday, $add=1)
    {
        if (is_object($oneParent)) $oneParent = (array)$oneParent;
        $code = strtolower($oneParent['code']);
        $linkReturn = BASE_URL;
        $weekday = $weekday + $add;
        $slugDay = $code . "-thu-";
        if ($weekday == 8 || $weekday == 1) {
            $slugDay = $code . "-chu-nhat";
            $weekday = '';
        };
        if ($weekday == '' && $oneParent['id'] > 40) {
            $slugDay = $code . "-cn";
            $weekday = '';
        }
        $linkReturn .= "$slugDay$weekday.html";
        return $linkReturn;
    }
}

if (!function_exists('getUrlDate')) {
    function getUrlDate($oneParent, $date)
    {
        if (is_object($oneParent)) $oneParent = (array)$oneParent;
        $linkReturn = strtolower($oneParent['code']).'-'. date('d-m-Y', strtotime($date));
        return $linkReturn;
    }
}

if (!function_exists('getUrlQuaythu')) {
    function getUrlQuaythu($code)
    {
        return base_url('quay-thu-'.strtolower($code));
    }
}

if (!function_exists('getUrlSoiCau')) {
    function getUrlSoiCau($code)
    {
        return base_url('soi-cau-'.strtolower($code).'.html');
    }
}

