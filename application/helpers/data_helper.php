<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('getByIdPage')) {
    function getByIdPage($id)
    {
        $_this = &get_instance();
        $_this->load->model('category_model');
        $model = new Category_model();
        $data = $model->getById($id);
        return $data;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key_setting, $name = '')
    {
        $instance = &get_instance();
        $instance->load->model('setting_model');
        $setting_model = new Setting_model();
        $data = $setting_model->get_setting_by_key($key_setting);
        $data = !empty($data->value_setting) ? json_decode($data->value_setting) : '';
        return !empty($data) ? (!empty($name) ? $data->$name : $data) : '';
    }
}
if (!function_exists('getDrag')) {
    function getDrag($type)
    {
        $_this = &get_instance();
        $_this->load->model('drag_model');
        $model = new Drag_model();
        $data = $model->getDataDrag($type);
        return $data;
    }
}
if (!function_exists('isMobileDevice')) {
    function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}

if (!function_exists('getTag')) {
    function getTag($args = array())
    {
        $default = [
            'limit' => 10,
            'type' => 'tag',
            'order' => ['id' => 'DESC']
        ];

        $params = array_merge($default, $args);
        $_this = &get_instance();
        $_this->load->model('category_model');
        $model = new Category_model();
        $data = $model->getDataFE($params);
        return $data;
    }
}
if (!function_exists('getDataPost')) {
    function getDataPost($params = array())
    {
        $_this = &get_instance();
        $_this->load->model('post_model');
        $model = new Post_model();
        $data = $model->getDataFE($params);
        return $data;
    }
}

if (!function_exists('getDataCategory')) {
    function getDataCategory($type = '')
    {
        $_this = &get_instance();
        $_this->load->model('category_model');
        $model = new Category_model();
        $data = $model->_all_category($type);
        return $data;
    }
}
if (!function_exists('getCategoryChild')) {
    function getCategoryChild($parent_id = 0)
    {
        $_this = &get_instance();
        $_this->load->model('category_model');
        $model = new Category_model();
        $model->_recursive_child($model->_all_category(), $parent_id);
        return $model->_list_category_child;
    }
}

if (!function_exists('getAllTournamentId')) {
    function getAllTournamentId()
    {
        $_this = &get_instance();
        $_this->load->model('category_model');
        $model = new Category_model();
        return $model->getAllTournamentId();
    }
}

if (!function_exists('getRate')) {
    function getRate($args = array())
    {
        $_this = &get_instance();
        $_this->load->model('reviews_model');
        $reviews = new Reviews_model();
        $data = $reviews->getRate($args);
        return $data;
    }
}
if (!function_exists('callCURL')) {
    function callCURL($url, $data = array(), $type = "GET")
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);

        if ($type == "POST") {
            curl_setopt($resource, CURLOPT_POST, true);
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_TIMEOUT, 40);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }
}

if (!function_exists('data_bet')) {
    function data_bet($bet_str)
    {
        if (!empty($bet_str)) {
            $bet_data = convert_bet_data($bet_str);
            $result = '';
            $asia = array();
            $asia[0] = isset($bet_data['live'][7]) ? $bet_data['live'][7] : '';
            $asia[1] = isset($bet_data['live'][8]) ? $bet_data['live'][8] : '';
            $asia[2] = isset($bet_data['live'][9]) ? $bet_data['live'][9] : '';
            $asia = parse_part_bet_data($asia);

            $uk = array();
            $uk[0] = isset($bet_data['live'][0]) ? $bet_data['live'][0] : '';
            $uk[1] = isset($bet_data['live'][1]) ? $bet_data['live'][1] : '';
            $uk[2] = isset($bet_data['live'][2]) ? $bet_data['live'][2] : '';

            $big = array();
            $big[0] = isset($bet_data['live'][11]) ? $bet_data['live'][11] : '';
            $big[1] = isset($bet_data['live'][12]) ? $bet_data['live'][12] : '';
            $big[2] = isset($bet_data['live'][13]) ? $bet_data['live'][13] : '';
            $big = parse_part_bet_data($big);

            $result .= '<p class="mb-1"><span class="font-weight-bold">' . $asia[0] . '*' . $asia[1] . '*' . $asia[2] . '</span> <span>(Châu Á)</span></p>';
            $result .= '<p class="mb-1"><span class="font-weight-bold">' . $uk[0] . '/' . $uk[1] . '/' . $uk[2] . '</span> <span>(Châu Âu)</span></p>';
            $result .= '<p class="mb-1"><span class="font-weight-bold">' . $big[0] . '*' . $big[1] . '*' . $big[2] . '</span> <span>(Tài xỉu)</span></p>';
        } else {
            $result = '<span>Dữ liệu đang được cập nhật</span>';
        }
        return $result;
    }
}

if (!function_exists('convert_bet_data')) {
    function convert_bet_data($str_bet_data)
    {
        $bet_data_ar = explode(';', $str_bet_data);
        $bet_data = array();
        for ($i = 0; $i < count($bet_data_ar); ++$i) {
            if ($bet_data_ar[$i] == 'Crown') {
                if ($bet_data_ar[$i + 1] != '') {
                    $bet_data['data'] = explode(',', $bet_data_ar[$i + 1]);
                }
                if ($bet_data_ar[$i + 2]) {
                    $bet_data['live'] = explode(',', $bet_data_ar[$i + 2]);
                }
                break;
            } elseif ($bet_data_ar[$i] == 'Bet365') {
                if ($bet_data_ar[$i + 1] != '') {
                    $bet_data['data'] = explode(',', $bet_data_ar[$i + 1]);
                }
                if ($bet_data_ar[$i + 2]) {
                    $bet_data['live'] = explode(',', $bet_data_ar[$i + 2]);
                }
                break;
            } elseif ($bet_data_ar[$i] == '10BET') {
                if ($bet_data_ar[$i + 1] != '') {
                    $bet_data['data'] = explode(',', $bet_data_ar[$i + 1]);
                }
                if ($bet_data_ar[$i + 2]) {
                    $bet_data['live'] = explode(',', $bet_data_ar[$i + 2]);
                }
                break;
            }
        }
        $bet_data = parse_bet_data($bet_data);
        return $bet_data;
    }
}

if (!function_exists('parse_part_bet_data')) {
    function parse_part_bet_data($bet)
    {

        foreach ($bet as $index => &$data) {

            if ($index == 1) {
                continue;
            }

            if ($data !== '') {
                if (strpos($data, '/') !== false) {
                    $data = explode('/', $data);
                    $data = $data[0] / $data[1];
                }

                $data = (float)$data;

                if ($data > 1) {
                    $data = $data - 2;
                }

                $data = number_format($data, 2);
            }
        }
        return $bet;
    }
}

if (!function_exists('parse_bet_data')) {
    function parse_bet_data($bet)
    {
        $run = [7, 9, 11, 13];
        foreach ($run as $i) {
            if (!isset($bet['live'][$i])) {
                continue;
            }

            $data = (float)$bet['live'][$i];

            if ($data > 1) {
                $data = $data - 2;
            }

            $data = number_format($data, 2);
            $bet['live'][$i] = $data;
        }

        foreach ($run as $j) {
            if (!isset($bet['data'][$j])) {
                continue;
            }

            $data = (float)$bet['data'][$j];

            if ($data > 1) {
                $data = $data - 2;
            }

            $data = number_format($data, 2);
            $bet['data'][$j] = $data;
        }

        return $bet;
    }
}
if (!function_exists('closetags')) {
    function closetags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}

if (!function_exists('array_group_by')) {
    function array_group_by($arr, callable $key_selector)
    {
        $arr = json_decode(json_encode($arr), true);
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($key_selector, $i);
            $result[$key][] = (object)$i;
        }
        return $result;
    }
}

if (!function_exists('getCateById')) {
    function getCateById($id)
    {
        $ci = &get_instance();
        $ci->load->model(['category_model']);
        $_data_category = new Category_model();
        return $_data_category->getByIdCached($id);
    }
}

if (!function_exists('getReward')) {
    function getReward($category)
    {
        if ($category == 1) {
            $reward = [
                0 => '',
                1 => 'ĐB',
                2 => 'G1',
                3 => 'G2',
                4 => 'G3',
                5 => 'G4',
                6 => 'G5',
                7 => 'G6',
                8 => 'G7'
            ];
        } else {
            $reward = [
                0 => 'G8',
                1 => 'G7',
                2 => 'G6',
                3 => 'G5',
                4 => 'G4',
                5 => 'G3',
                6 => 'G2',
                7 => 'G1',
                8 => 'ĐB'
            ];
        }
        return $reward;
    }
}
if (!function_exists('getRewardVietlot')) {
    function getRewardVietlot($category)
    {
        if ($category == 44) {
            $reward = [
                0 => 'G.1',
                1 => 'G.2',
                2 => 'G.3',
                3 => 'KK'
            ];
        } else {
            $reward = [
                0 => 'Giải Nhất',
                1 => 'G.2',
                2 => 'G.3',
                3 => 'KK'
            ];
        }
        return $reward;
    }
}

if (!function_exists('groupDisTime')) {
    function groupDisTime($dataApi)
    {
        $groupDisplaytime = array();
        if (!empty($dataApi)) {
            foreach ($dataApi as $key => $value) {
                $groupDisplaytime[$value['displayed_time']][] = $value;
            }
        }
        return $groupDisplaytime;
    }
}


if (!function_exists('getLoto')) {
    function getLoto($result, $return = '', $length = '')
    {
        if (is_array($result) == FALSE) $result = json_decode($result);
        $arrNumber = [];
        if(!empty($result)) foreach ($result as $item) {
            if(!empty($item)) foreach ($item as $number) {
                if(is_numeric($number)) {
                    if ($length == 'full') $arrNumber[] = $number;
                    else $arrNumber[] = substr($number, -2);
                }
            }
        }
        if ($return == 'loto') return $arrNumber;
        $arrResult = [];
        for ($i = 0; $i <= 9; $i++) {
            $tmp['head'] = getValueLoto($i, $arrNumber, false);
            $tmp['tail'] = getValueLoto($i, $arrNumber, true);
            $arrResult[] = $tmp;
        }
        return $arrResult;
    }
    function getValueLoto($numberCheck, $listNumber, $is_head = true)
    {
        $string = '';
        if (!empty($listNumber)) foreach ($listNumber as $number) {
            if($is_head == true && $numberCheck == $number[0]) $string .= "<span>$number[1]</span>";
            if($is_head != true && $numberCheck == $number[1]) $string .= "<span>$number[0]</span>";
        }
        return $string;
    }
}

if (!function_exists('getCatByWeekDay')) {
    function getCatByWeekDay($weekDay)
    {
        $ci = &get_instance();
        $nameCache = "sidebarLeftCatbywd_{$weekDay}";
        $result = $ci->getCache($nameCache);
        if (empty($result)) {
            $ci->load->model('category_model');
            $category_model = new Category_model();
            $result = [];
            $type = 'lottery';
            $cat_result = $category_model->getListRecursive($type);
            if (!empty($cat_result)) {
                $cat_MB = $category_model->getByCode('xsmb');
                $cat_MT = $cat_result[1]->list_child;
                $cat_MN = $cat_result[2]->list_child;
                $cat_child = array_merge($cat_MN, $cat_MT);
                if (!empty($cat_child)) {
                    foreach ($cat_child as $key => $item) {
                        $arr = json_decode($item->weekday, true);
                        if ($weekDay !== null && in_array($weekDay, $arr)) {
                            $result[] = $item;
                        }
                    }
                    array_push($result, $cat_MB);
                    $result = array_reverse($result);
                    $ci->setCache($nameCache, $result, 10);
                }
            }
        }
        return $result;
    }
}

if (!function_exists('getResultVietlot')) {
    function getResultVietlot($categoryId)
    {
        $ci = &get_instance();
        $nameCache = "ResultId_{$categoryId}";
        $result = $ci->getCache($nameCache);
        if (empty($result)) {
            $urlApi_vietlot = API_DATACENTER . "api/v1/result/getdataresult?api_id=$categoryId&limit=1";
            $data_json_vietlot = json_decode(callCURL($urlApi_vietlot), true);
            $result = (!empty($data_json_vietlot)) ? $data_json_vietlot['data']['data'] : array();
            $ci->setCache($nameCache, $result);
        }
        return $result;
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

if (!function_exists('sidebarCategory')) {
    function sidebarCategory($id)
    {
        $ci = &get_instance();
        $nameCache =    "sidebarCategory_{$id}";
        $data =   $ci->getCache($nameCache);
        if (empty($result)) {
            $ci->load->model('category_model');
            $_data_category = new Category_model();
            $param = [
                'parent_id' => $id,
                'is_status' => 1
            ];
            $data = $_data_category->getDataFE($param);
            $ci->setCache($nameCache, $data, 3600);
        }
        return $data;
    }
}

function viewResultMB($data = []){
    $_this = &get_instance();
    if (isset($data[0])) $data = $data[0];
    return $_this->load->view(TEMPLATE_PATH.'lottery/_result-xsmb', ['data' => $data], true);
}
function viewResultSoiCau($data = []){
    $_this = &get_instance();
    if (isset($data[0])) $data = $data[0];
    return $_this->load->view(TEMPLATE_PATH.'soicau/_result', ['data' => $data], true);
}
function viewResultSoiCauProvince($data = []){
    $_this = &get_instance();
    if (isset($data[0])) $data = $data[0];
    return $_this->load->view(TEMPLATE_PATH.'soicau/_result-province', ['data' => $data], true);
}
function viewResultProvince($oneItem, $data = []){
    $_this = &get_instance();
    if (isset($data[0])) $data = $data[0];
    return $_this->load->view(TEMPLATE_PATH.'lottery/_result-province', [
        'oneItem' => $oneItem,
        'data' => $data
    ], true);
}
function viewResultMTMN($oneItem, $data = [], $dow = null){
    $_this = &get_instance();
    return $_this->load->view(TEMPLATE_PATH.'lottery/_result-mt_mn', [
        'oneItem' => $oneItem,
        'data' => $data,
        'dow' => $dow
    ], true);
}
function viewLotoSingle($data = []){
    if (isset($data[0])) $data = $data[0];
    $_this = &get_instance();
    return $_this->load->view(TEMPLATE_PATH.'lottery/_loto-single', ['data' => $data], true);
}
function viewLotoMulti($oneItem, $data = [], $dow = null){
    $_this = &get_instance();
    return $_this->load->view(TEMPLATE_PATH.'lottery/_loto-multi', [
        'oneItem' => $oneItem,
        'data' => $data,
        'dow' => $dow
    ], true);
}

function getSpinTime($code){
    $code = strtoupper($code);

    $code = in_array($code, ['MAX3DPLUS', 'MAX3DPRO']) ? 'MAX3D' : $code;

    $_this = &get_instance();
    $_this->load->model('category_model');
    $cateModel = new Category_model();
    $spinTime = $cateModel->getSpinTime();

    return isset($spinTime[$code]) ? $spinTime[$code] : [];
}

function getCatChildByDOW($parentId = 0, $dayOfWeek = null)
{
    $ci =& get_instance();
    $ci->load->model('category_model');
    $categoryModel = new Category_model();
    $rs = [];
    $_all_category = $categoryModel->_all_category();
    $data = $categoryModel->getListChild($_all_category, $parentId);
    if (!empty($data)) foreach ($data as $key => $item) {
        if ($dayOfWeek !== null && strpos($item->weekday, "$dayOfWeek") !== false) {
            $rs[] = $data[$key];
        } else if ($dayOfWeek === null){
            $rs = $data;
        }
    }
    return $rs;
}

if (!function_exists('getCateByCode')) {
    function getCateByCode($code)
    {
        $ci = &get_instance();
        $ci->load->model(['category_model']);
        $_data_category = new Category_model();
        return $_data_category->getByField('code', $code);
    }
}