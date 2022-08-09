<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getAllBrand')) {
    function getAllBrand()
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $categoryModel = new Category_model();
        return (object)$categoryModel->getListRecursive('brand', 0);
    }
}

if (!function_exists('countProductByCate')) {
    function countProductByCate($cateId)
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $catModel = new Product_model();
        $data = $catModel->countPostByCate($cateId);
        return $data;
    }
}

if (!function_exists('countProductByType')) {
    function countProductByType($typeId, $categoryId)
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $productModel = new Product_model();
        $data = $productModel->countProductByType($typeId, $categoryId);
        return $data;
    }
}
if (!function_exists('getListProduct')) {
    function getListProduct($ids, $limit = 10)
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $productModel = new Product_model();
        $params = array(
            'is_status' => 1,
            'in' => $ids,
            'limit' => $limit
        );
        $data = $productModel->getData($params);
        return $data;
    }
}

if (!function_exists('getProductDetail')) {
    function getProductDetail($id)
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $productModel = new Product_model();
        $data = $productModel->getDetail($id);
        return $data;
    }
}
if (!function_exists('getProduct')) {
    function getProduct($id, $select = '*', $language = '')
    {
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->model('product_model');
        $productModel = new Product_model();
        if (empty($language)) $language = !empty($_this->session->public_lang_code) ? $_this->session->public_lang_code : $_this->session->admin_lang;
        $oneData = $productModel->getById($id, $select, $language);
        return $oneData;
    }
}

if (!function_exists('getPostByHash')) {
    function getPostByHash($source_hash, $select = '*'){
        $_this =& get_instance();
        $_this->load->model('post_model');
        $postModel = new Post_model();
        $oneData = $postModel->getByField('source_hash',$source_hash,$select,$_this->session->public_lang_code);
        return $oneData;
    }
}
if (!function_exists('getMadeIn')) {
    function getMadeIn($cateId)
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $catModel = new Category_model();
        if (!$_this->cache->get('_all_category_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_category_' . $_this->session->public_lang_code, $_this->_data_category->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->cache->get('_all_category_' . $_this->session->public_lang_code);
        $data = $catModel->getByIdCached($_all_category, $cateId);
        return !empty($data) ? $data->title : '';
    }

}

if (!function_exists('getAddressById')) {
    function getAddressById($districtId)
    {
        $_this =& get_instance();
        $_this->load->model('location_model');
        $catModel = new Location_model();
        $data = $catModel->getDistrictById($districtId);
        return !empty($data->path_with_type) ? $data->path_with_type : '';
    }

}

if (!function_exists('getCategoryChildLv1')) {
    function getCategoryChildLv1($parentId = 0)
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $categoryModel = new Category_model();
        if (!$_this->cache->get('_all_category_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_category_' . $_this->session->public_lang_code, $categoryModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->cache->get('_all_category_' . $_this->session->public_lang_code);
        $data = $categoryModel->getListChild($_all_category, $parentId);
        return $data;
    }
}

if (!function_exists('getCategoryChildAll')) {
    function getCategoryChildAll($parentId = 0)
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $categoryModel = new Category_model();
        if (!$_this->cache->get('_all_category_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_category_' . $_this->session->public_lang_code, $categoryModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->cache->get('_all_category_' . $_this->session->public_lang_code);
        $categoryModel->_queue_select($_all_category, $parentId);
        return $categoryModel->category_tree;
    }
}
if (!function_exists('getCategoryByType')) {
    function getCategoryByType($parentId = 0, $type = 'post')
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $categoryModel = new Category_model();
        if (!$_this->cache->get('_all_category_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_category_' . $_this->session->public_lang_code, $categoryModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->cache->get('_all_category_' . $_this->session->public_lang_code);
        $data = $categoryModel->getAllCategoryByType($_this->session->public_lang_code, $type, $parentId);
        return $data;
    }
}
if (!function_exists('getCategoryById')) {
    function getCategoryById($id)
    {
        $_this =& get_instance();
        $_this->load->model('category_model');
        $categoryModel = new Category_model();
        $data = $categoryModel->getById($id, '', $_this->session->public_lang_code);
        return $data;
    }
}

if (!function_exists('getPageById')) {
    function getPageById($id)
    {
        $_this =& get_instance();
        $_this->load->model('page_model');
        $dataModel = new Page_model();
        $data = $dataModel->getById($id, '', $_this->session->public_lang_code);
        return $data;
    }
}

if (!function_exists('getUserById')) {
    function getUserById($id)
    {
        $_this =& get_instance();
        $_this->load->model('users_model');
        $dataModel = new Users_model();
        $data = $dataModel->getById($id, '', $_this->session->public_lang_code);
        return $data;
    }
}

if (!function_exists('getListProductByCateId')) {
    function getListProductByCateId($id, $limit = 10, $featured = 0)
    {
        $_this =& get_instance();
        $_this->load->model(['product_model', 'category_model']);
        $categoryModel = new Category_model();
        $productModel = new Product_model();
        if (!$_this->cache->get('_all_category_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_category_' . $_this->session->public_lang_code, $categoryModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->cache->get('_all_category_' . $_this->session->public_lang_code);
        $categoryModel->_recursive_child_id($_all_category, $id);
        $params = array(
            'is_status' => 1,
            'is_featured' => $featured,
            'category_id' => $categoryModel->_list_category_child_id,
            'limit' => $limit
        );
        $data = $productModel->getData($params);
        return $data;
    }
}

if (!function_exists('getListNewsByCateId')) {
    function getListNewsByCateId($id, $limit = 10, $featured = 0)
    {
        $_this =& get_instance();
        $_this->load->model(['post_model', 'category_model']);
        $categoryModel = new Category_model();
        $postModel = new Post_model();
        if (!$_this->getCache('_all_category_' . $_this->session->public_lang_code)) {
            $_this->setCache('_all_category_' . $_this->session->public_lang_code, $categoryModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_category = $_this->getCache('_all_category_' . $_this->session->public_lang_code);
        $categoryModel->_recursive_child_id($_all_category, $id);
        $params = array(
            'is_status' => 1,
            'category_id' => $categoryModel->_list_category_child_id,
            'limit' => $limit,
            'order' => ['ngay_ban_hanh' => 'DESC']
        );
        if(!empty($featured)) $params['order']=array('is_featured'=>'DESC');
        $data = $postModel->getData($params);
        return $data;
    }
}

if (!function_exists('listBannerByPosition')) {
    function listBannerByPosition($position_id)
    {
        $_this =& get_instance();
        $_this->load->model('banner_model');
        $bannerModel = new Banner_model();
        $data = $bannerModel->getData(['lang_code' => $_this->session->public_lang_code, 'property_id' => $position_id]);
        return $data;
    }

}
if (!function_exists('covertMoney')) {
    function covertMoney($number)
    {
        if ($number != null) {
            return number_format($number, 0, '', '.');
        }
    }
}
if (!function_exists('showRatting')) {
    function showRatting($diem)
    {
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $diem) {
                echo '<i class="icon_star active"></i>';
            } else {
                echo '<i class="icon_star"></i>';
            }
        }
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

if (!function_exists('getTags')) {
    function getTags($keyword)
    {
        $tags = '';
        if (!empty($keyword)) {
            $listTags = explode(',', trim($keyword));
            if(!empty($listTags)) foreach ($listTags as $k => $tag) {
                $tag = trim($tag);
                if(!empty($tag) && $tag !== ''){
                    $tags .= $k != 0 ? ', ' : '';
                    $tags .= '<a href="' . getUrlTag($tag) . '" title="' . $tag . '">' . $tag . '</a>';
                }
            }
        }
        return $tags;
    }
}


if (!function_exists('getPropertyById')) {
    function getPropertyById($id)
    {
        $_this =& get_instance();
        $_this->load->model('property_model');
        $propertyModel = new Property_model();
        if (!$_this->cache->get('_all_property_' . $_this->session->public_lang_code)) {
            $_this->cache->save('_all_property_' . $_this->session->public_lang_code, $propertyModel->getAll($_this->session->public_lang_code), 60 * 60 * 30);
        }
        $_all_property = $_this->cache->get('_all_property_' . $_this->session->public_lang_code);
        $data = $propertyModel->getByIdCached($_all_property, $id);
        return $data;
    }
}


if (!function_exists('getPropertyByProduct')) {
    function getPropertyByProduct($productId, $type)
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $productModel = new Product_model();
        $data = $productModel->getPropertyByProduct($productId, $type);
        return $data;
    }
}

if (!function_exists('')) {
    function checkExistCart($productId)
    {
        $_this =& get_instance();
        $listItem = $_this->cart->contents();
        if (!empty($listItem)) foreach ($listItem as $item) {
            if ($item['id'] == $productId) return true;
        }
        return false;
    }
}
if (!function_exists('getPostNews')) {
    function getPostNews()
    {
        $_this =& get_instance();
        $_this->load->model('post_model');
        $postModel = new Post_model();
        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $_this->session->public_lang_code,
            'limit' => 5,
        );
        $data = $postModel->getData($params);
        return $data;
    }
}

if (!function_exists('getCompare')) {
    function getCompare()
    {
        $_this =& get_instance();
        $_this->load->model('product_model');
        $productModel = new Product_model();
        $key = 'product_compare';
        $dataId = get_cookie($key);
        $listProductId = json_decode($dataId, true);
        $params['is_status'] = 1;
        $params['lang_code'] = $_this->session->userdata('public_lang_code');
        $params['in'] = $listProductId;
        $params['limit'] = 10;
        return $productModel->getData($params);
    }
}
if (!function_exists('getCityById')) {
    function getCityById($id)
    {
        $_this =& get_instance();
        $_this->load->model('location_model');
        $locModel = new Location_model();
        $data = $locModel->getCityById($id);
        return !empty($data) ? $data->name : '';
    }
}
if (!function_exists('getDistrict')) {
    function getDistrict($id)
    {
        $_this =& get_instance();
        $_this->load->model('location_model');
        $locModel = new Location_model();
        $data = $locModel->getDistrictById($id);
        return !empty($data) ? $data->name : '';
    }
}

if (!function_exists('getFeedback')) {
    function getFeedback(){
        $_this =& get_instance();
        $_this->load->model('feedback_model');
        $feedbackModel = new Feedback_model();
        $data = $feedbackModel->getAll('',1);
        return $data;
    }
}

if (!function_exists('getMostViewed')) {
    function getMostViewed(){
        $_this =& get_instance();
        $_this->load->model(['post_model']);
        $postModel = new Post_model();
        $params = array(
            'lang_code' => $_this->session->userdata('public_lang_code'),
            'is_status' => 1,
            'limit' => 6,
            'order' => ['viewed' => 'desc']
        );
        return $postModel->getData($params);
    }
}

if (!function_exists('listGroupBy')) {
    function listGroupBy($field){
        $_this =& get_instance();
        $_this->load->model(['post_model']);
        $postModel = new Post_model();
        return $postModel->listGroupBy($field);
    }
}