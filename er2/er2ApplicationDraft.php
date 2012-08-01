<?php
/**
 * welcome.php for plugin er2
 *
 *
 */

try {
  /* Render page */
  $oHeadPublisher = &headPublisher::getSingleton();
  
  $G_MAIN_MENU        = "processmaker";
  $G_ID_MENU_SELECTED = "ID_ER2_MNU_01";
  //$G_SUB_MENU             = "setup";
  //$G_ID_SUB_MENU_SELECTED = "ID_ER2_02";

  $config = array();
  $config["pageSize"] = 15;
  $config["message"] = "Hello world!";
  $config["ws"] = $_SESSION["WORKSPACE"];
  $config["action"] = "draft";

  $oHeadPublisher->addContent("er2/er2Application"); //Adding a html file .html
  $oHeadPublisher->addExtJsScript("er2/er2Application", false); //Adding a javascript file .js
  $oHeadPublisher->assign("CONFIG", $config);

  G::RenderPage("publish", "extJs");
} catch (Exception $e) {
  $G_PUBLISH = new Publisher;
  
  $aMessage["MESSAGE"] = $e->getMessage();
  $G_PUBLISH->AddContent("xmlform", "xmlform", "er2/messageShow", "", $aMessage);
  G::RenderPage("publish", "blank");
}
?>