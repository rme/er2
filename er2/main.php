<?php

//$RBAC->requirePermissions('PM_SETUP');

$G_MAIN_MENU            = 'processmaker';
$G_ID_MENU_SELECTED     = 'ID_HR';
$G_PUBLISH = new Publisher;

if( isset($_GET['i18']) )
  $_SESSION['DEV_FLAG'] = $_SESSION['TOOLS_VIEWTYPE'] = isset($_GET['i18']);
else {
  unset($_SESSION['DEV_FLAG']);
  unset($_SESSION['TOOLS_VIEWTYPE']);
}

if( isset($_GET['s']) )
  $_SESSION['ADMIN_SELECTED'] = $_GET['s'];
else {
  unset($_SESSION['ADMIN_SELECTED']);
}

$G_PUBLISH->AddContent('view', 'setup/main_Load');
G::RenderPage('publish');

