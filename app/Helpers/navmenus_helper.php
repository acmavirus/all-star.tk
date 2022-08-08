<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('navMenuTop')) {
    function navMenuTop($class_name = '', $id = '', $sub_menu_class = ''){
        return menus(1, $class_name, $id, $sub_menu_class);
    }
}
if (!function_exists('navMenuMain')) {
    function navMenuMain($classname = '', $id = '', $submenuclass = ''){
        return menus(0, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navMenuMainMobile')) {
    function navMenuMainMobile($classname = '', $id = '', $submenuclass = ''){
        return menus(0, $classname, $id, $submenuclass);
    }
}


if (!function_exists('navSoikeo')) {
    function navSoikeo ($classname = '', $id = '', $submenuclass = ''){
        return menush5(6, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navLTD')) {
    function navLTD ($classname = '', $id = '', $submenuclass = ''){
        return menush5(7, $classname, $id, $submenuclass);
    }
}
if (!function_exists('navKQ')) {
    function navKQ ($classname = '', $id = '', $submenuclass = ''){
        return menush5(8, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navBXH')) {
    function navBXH ($classname = '', $id = '', $submenuclass = ''){
        return menush5(9, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navMenuFooter')) {
    function navMenuFooter ($classname = '', $id = '', $submenuclass = ''){
        return menus(2, $classname, $id, $submenuclass);
    }
}
if (!function_exists('navFooter1')) {
    function navFooter1 ($classname = '', $id = '', $submenuclass = ''){
        return menush5(3, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navFooter2')) {
    function navFooter2 ($classname = '', $id = '', $submenuclass = ''){
        return menush5(4, $classname, $id, $submenuclass);
    }
}

if (!function_exists('navFooter3')) {
    function navFooter3 ($classname = '', $id = '', $submenuclass = ''){
        return menush5(5, $classname, $id, $submenuclass);
    }
}


function menus($location, $classname, $id, $submenuclass){
    $ci =& get_instance();
    $ci->load->model('menus_model');
    $ci->load->library('NavsMenu');
    $ci->load->helper('link');
    $menuModel = new Menus_model();
    $q = $menuModel->getMenu($location);
    $menuModel->listmenu($q);
    $listMenu = $menuModel->listmenu;
    $navsMenu = new NavsMenu();
    $navsMenu->set_items($listMenu);
    $config["item_tag_open"]     = '<li class="%s nav-item">';
    $config["item_tag_close"]    = '</li>';
    $config["nav_tag_open"]          = "<ul id='$id' class='$classname'>";
    $config["parent_tag_open"]       = "<li class='%s '>";
    $config["item_anchor"]          = "<a class=\"\" href=\"%s\" title=\"%s\">%s</a>";
    $config["parent_anchor"]          = "<a class=\"\" href=\"%s\" title=\"%s\">%s</a>";
    $config["item_active_class"]       = "active";
    $config["children_tag_open"]     = "<ul class='$submenuclass'>";
    $navsMenu->initialize($config);
    $menuHtml = $navsMenu->render();
    return $menuHtml;

}
function menush5($location, $class_name, $id, $submenu_class){
    $ci =& get_instance();
    $ci->load->model('menus_model');
    $ci->load->library('NavsMenu');
    $ci->load->helper('link');
    $menuModel = new Menus_model();
    $q = $menuModel->getMenu($location);

    $menuModel->listmenu($q);
    $listMenu = $menuModel->listmenu;
    $navsMenu = new NavsMenu();
    $navsMenu->set_items($listMenu);
    $config["nav_tag_open"]          = "<ul class='$class_name' id='$id'>";
    $config["parent_tag_open"]     = '<li class="nav-item has-sub-menu">';
    $config["item_tag_open"]     = '<li class="nav-item"><h5>';
    $config["item_tag_close"]     = '</h5></li>';
    $config["item_active_class"] = 'active';
    $config["children_tag_open"]     = "<ul class='$submenu_class'>";
    $navsMenu->initialize($config);
    $menuHtml = $navsMenu->render();
    return $menuHtml;
}
?>
