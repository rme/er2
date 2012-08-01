<?php
  G::LoadClass('configuration');
  G::LoadClass ('pmFunctions');
  $G_PUBLISH = new Publisher;  
 /* if (isset($_POST['form']['SUBMIT']))
  { 
  	  $_POST['form']['FLAG_SAVE'] = 1;
   		$usr_uid = $_POST['form']['NAME'];
		  $user    = executeQuery("SELECT USR_FIRSTNAME,USR_LASTNAME FROM USERS WHERE USR_UID='$usr_uid'");
		  $firstname = $user[1]['USR_FIRSTNAME'];
		  $lastname  = $user[1]['USR_LASTNAME'];
		  //Load the variables 
		  $guid              = $_POST['form']['GUID'];
		  $profile_centre    = $_POST['form']['COST_CENTRE'];
		  $mileage_member_no = $_POST['form']['MILEAGE_MEMBER_NO'];
		  $oversea_claim_id  = $_POST['form']['OVERSEA_CLAIM_CLASSIFICATION'];
		  $domest_expenses   = $_POST['form']['DOMEST_EXPENSES_LIMIT'];
		  $domest_enter      = $_POST['form']['DOMEST_ENTERTAINMENT_LIMIT'];
		  $domest_medical    = $_POST['form']['DOMEST_MEDICAL_LIMIT'];
		  $domest_mobile     = $_POST['form']['DOMEST_MOBILE_LIMIT'];
		  $domest_trans      = $_POST['form']['DOMEST_TRANSPORTATION_LIMIT'];
		  		
  }*/

  	$REPORT_GRID = array();

$aDataRow = $_POST['grid'];
G::pr($aDataRow);die;
	  foreach($aDataRow as $rows)
	  {
	  	//$GRID_INSUMOS_IMP[$count]['TXT_DESCRIPCION'] = $rows[0];
	  	 

	  } 
  $POST = $_POST['grid'];
  $G_PUBLISH->AddContent( 'dynaform', 'xmlform', 'er2/setup_form','',$POST);
  G::RenderPage( "publish","raw");
?>
