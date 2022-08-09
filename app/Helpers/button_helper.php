<?php

if (!function_exists('button_admin')) {
    function button_admin($args = array('add', 'delete'))
    {
        showButtonAdd();
        showButtonDelete();
        showButtonReload();
    }
}
if (!function_exists('showButtonAdd')) {
    function showButtonAdd()
    {
        echo '<a href="javascript:;" class="btn btn-primary m-btn m-btn--icon m-btn--air m-btn--pill btnAddForm">
                <span>Add</span>
            </a> ';
    }
}

if (!function_exists('showButtonDelete')) {
    function showButtonDelete()
    {
        echo '<a href="javascript:;" class="btn btn-danger m-btn m-btn--icon m-btn--air m-btn--pill btnDeleteAll">
                <span>Delete</span>
            </a> ';
    }
}

if (!function_exists('showButtonReload')) {
    function showButtonReload()
    {
        echo '<a href="javascript:;" class="btn btn-info m-btn m-btn--icon m-btn--air m-btn--pill btnReload">
                <span>Refresh</span>
            </a>';
    }
}
