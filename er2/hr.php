<?php

global $G_TMP_MENU;
global $RBAC;

if ($RBAC->userCanAccess('PM_SETUP') == 1 ) {
  //$G_TMP_MENU->AddIdRawOption('ID_EMPLOYEE_09', '../er2/er2StartCaseOriginal', 'New case (original)', 'icon-pmlogo.png', '', 'Expenses Report');
  $G_TMP_MENU->AddIdRawOption('ID_EMPLOYEE_11', '../er2/er2ApplicationTodo', 'Inbox', 'icon-pmlogo.png', '', 'Expenses Report');
  $G_TMP_MENU->AddIdRawOption('ID_EMPLOYEE_10', '../er2/er2StartCase', 'New case', 'icon-pmlogo.png', '', 'Expenses Report', 'blockHeader');
  $G_TMP_MENU->AddIdRawOption('ID_EMPLOYEE_12', '../er2/er2ApplicationDraft', 'Draft', 'icon-pmlogo.png', '', 'Expenses Report');
  $G_TMP_MENU->AddIdRawOption('ID_EMPLOYEE_13', '../er2/er2ApplicationParticipated', 'Participated', 'icon-pmlogo.png', '', 'Expenses Report');
}