<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('navMenuTop')) {
    function navMenuTop($classname = '', $id = '', $submenuclass = ''){
        return menus(0, $classname, $id, $submenuclass);
    }
}
if (!function_exists('navMenuMain')) {
    function navMenuMain($classname = '', $id = '', $submenuclass = ''){

        return menusHome(0, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navMenuFooter1')) {
    function navMenuFooter1($classname = '', $id = '', $submenuclass = ''){
        return menus(1, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navMenuFooter2')) {
    function navMenuFooter2($classname = '', $id = '', $submenuclass = ''){
        return menus(2, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navMenuFooter3')) {
    function navMenuFooter3($classname = '', $id = '', $submenuclass = ''){
        return menus(3, $classname, $id, $submenuclass);
    }
}

function menus($location, $classname, $id, $submenuclass){
    $ci =& get_instance();
    $ci->load->model('menus_model');
    $ci->load->library('NavsMenu');
    $ci->load->helper('link');
    $menuModel = new Menus_model();
    $q = $menuModel->getMenu($location, $ci->session->public_lang_code);
    $menuModel->listmenu($q);
    $listMenu = $menuModel->listmenu;
    $navsMenu = new NavsMenu();
    $navsMenu->set_items($listMenu);
    $config["nav_tag_open"]          = "<ul id='$id' class='$classname'>";
    $config["parent_tag_open"]       = "<li class='%s'>";
    if($location == 1){
        $config["item_anchor"]          = "<a href=\"%s\" title=\"%s\">%s</a>";
        $config["parent_anchor"]          = "<a href=\"%s\" title=\"%s\">%s</a>";
    }
    $config["item_active_class"]       = "active";
    $config["children_tag_open"]     = "<ul class='$submenuclass'>";
    $navsMenu->initialize($config);
    $menuHtml = $navsMenu->render();
    return $menuHtml;

}
function menusHome($location, $classname, $id, $submenuclass){
    $ci =& get_instance();
    $ci->load->model('menus_model');
    $ci->load->library('NavsMenu');
    $ci->load->helper('link');
    $menuModel = new Menus_model();
    $q = $menuModel->getMenu($location, $ci->session->public_lang_code);
    $menuModel->listmenu($q);
    $listMenu = $menuModel->listmenu;
    $navsMenu = new NavsMenu();
    $navsMenu->set_items($listMenu);
    $config["nav_tag_open"]          = "<ul id='$id' class='$classname'>";
    $config["parent_tag_open"]       = "<li class='%s nav-item'>";
    $config["item_active_class"]       = "active";
    $config["children_tag_open"]     = "<ul class='$submenuclass'>";
    $navsMenu->initialize($config);
    $menuHtml = $navsMenu->render();
    return $menuHtml;

}

function menuCategory($location, $classname, $id, $submenuclass){
    $ci =& get_instance();
    $ci->load->model('menus_model');
    $ci->load->library('NavsMenu');
    $ci->load->helper('link');
    $menuModel = new Menus_model();
    $q = $menuModel->getMenu($location, $ci->session->public_lang_code);
    $menuModel->listmenu($q);
    $listMenu = $menuModel->listmenu;
    $navsMenu = new NavsMenu();
    $navsMenu->set_items($listMenu);
    $config["nav_tag_open"]          = "<ul id='$id' class='$classname'>";
    $config["parent_tag_open"]       = "<li class='%s has-child'>";
    $config["item_active_class"]       = "active";
    $config["children_tag_open"]     = "<div class='wrap-popup column1'><div class='popup'><ul class='$submenuclass'>";
    $config["children_tag_close"]     = "</ul></div</div";
    $navsMenu->initialize($config);
    $menuHtml = $navsMenu->render();
    return $menuHtml;

}

?>
