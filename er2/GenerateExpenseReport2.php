<?php
/* Includes */
G::LoadClass ( "pmFunctions" );
G::LoadClass ( 'configuration' );
G::LoadClass ( 'case' );
require_once ("classes/model/Application.php");
require_once('public_html/tcpdf/config/lang/eng.php');
require_once('public_html/tcpdf/tcpdf.php');
require('public_html/tcpdf/htmlcolors.php');
/*End Includes*/

//Parameters
$appuid = $_SESSION ['APPLICATION'];
$index = $_SESSION ['INDEX'];
$process = $_SESSION ['PROCESS'];
$logo = '';
$uidform = $_GET ['uidform'];
// Obtain The Info Of The User

$Server = "http://".$_SERVER['HTTP_HOST'];
$info = userinfo ( $_SESSION ['USER_LOGGED'] );
$usrInfo = $info ['firstname'] . " " . $info ['lastname'];
// End Obtain The Info Of The User

$createdDocuments = "SELECT OUTPUT_UID FROM REI_DYNAFORM_OUTPUT WHERE DYN_UID = '" . $uidform . "'";
$createdDocuments1 = executeQuery ( $createdDocuments );
if (sizeof ( $createdDocuments1 )) {
    $uidOuput = $createdDocuments1 [1] ['OUTPUT_UID'];
}
// Open the case
$oCaseObj = new Cases ();
$FieldsCase = $oCaseObj->loadCase ( $_SESSION ['APPLICATION'] );
$APP_DATA = $FieldsCase ['APP_DATA'];
//Grid Report
$cadena='';
$i=0;
$GRID=$APP_DATA ['REPORT_GRID'];
foreach($GRID as $row)
{
 $i++;
 $cadena=$cadena.'<tr><td class="FormCellTable"><font size="7">'.$i.'</font></td>';
 foreach($row as $key => $dato) {
      if(strpos($key,"label")==false)
        $cadena=$cadena.'<td class="FormCellTable"><font size="7">'.$dato.'</font></td>';
 }
 $cadena=$cadena.'</tr>';
}
$APP_DATA ['GRID_TABLE']=$cadena;

################################## Generate documents####################################################################   

$oOutputDocument = new OutputDocument ();
$aOD = $oOutputDocument->load ( $uidOuput );
$sFilename = ereg_replace ( '[^A-Za-z0-9_]', '_', G::replaceDataField ( $aOD ['OUT_DOC_FILENAME'], $APP_DATA ) );
if ($sFilename == '')
    $sFilename = '_';  
$pathOutput = PATH_DOCUMENT . $appuid . PATH_SEP . 'outdocs' . PATH_SEP;
G::mk_dir ( $pathOutput );
$aProperties = array ();
if (! isset ( $aOD ['OUT_DOC_MEDIA'] ))
    $aOD ['OUT_DOC_MEDIA'] = 'Letter';
if (! isset ( $aOD ['OUT_DOC_LEFT_MARGIN'] ))
  $aOD ['OUT_DOC_LEFT_MARGIN'] = '10';
if (! isset ( $aOD ['OUT_DOC_RIGHT_MARGIN'] ))
  $aOD ['OUT_DOC_RIGHT_MARGIN'] = '10';
if (! isset ( $aOD ['OUT_DOC_TOP_MARGIN'] ))
  $aOD ['OUT_DOC_TOP_MARGIN'] = '10';
if (! isset ( $aOD ['OUT_DOC_BOTTOM_MARGIN'] ))
  $aOD ['OUT_DOC_BOTTOM_MARGIN'] = '10';
$aProperties ['media'] = $aOD ['OUT_DOC_MEDIA'];
$aProperties ['margins'] = array ('left' => $aOD ['OUT_DOC_LEFT_MARGIN'], 'right' => $aOD ['OUT_DOC_RIGHT_MARGIN'], 'top' => $aOD ['OUT_DOC_TOP_MARGIN'], 'bottom' => $aOD ['OUT_DOC_BOTTOM_MARGIN'] );
$CheckDocuments = "SELECT AD.APP_DOC_UID FROM APP_DOCUMENT AD WHERE AD.APP_UID = '" . $appuid . "' AND APP_DOC_TYPE='OUTPUT' AND DOC_UID='" . $uidOuput . "' ";
$CheckDocuments1 = executeQuery ( $CheckDocuments );
//Verifica q si guecreado el documento
if (sizeof ( $CheckDocuments1 ) == 0) {
    $oAppDocument = new AppDocument ();
    $lastDocVersion = $oAppDocument->getLastDocVersion ( $uidOuput, $appuid );
    $oCriteria = new Criteria ( 'workflow' );
    $oCriteria->add ( AppDocumentPeer::APP_UID, $appuid );
    $oCriteria->add ( AppDocumentPeer::DOC_UID, $uidOuput );
    $oCriteria->add ( AppDocumentPeer::DOC_VERSION, $lastDocVersion );
    $oCriteria->add ( AppDocumentPeer::APP_DOC_TYPE, 'OUTPUT' );
    $oDataset = AppDocumentPeer::doSelectRS ( $oCriteria );
    $oDataset->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
    $oDataset->next ();
    
    $aFields = array ('APP_UID' => $appuid, 'DEL_INDEX' => $index, 'DOC_UID' => $uidOuput, 'DOC_VERSION' => $lastDocVersion + 1, 'USR_UID' => $_SESSION ['USER_LOGGED'], 'APP_DOC_TYPE' => 'OUTPUT', 'APP_DOC_CREATE_DATE' => date ( 'Y-m-d H:i:s' ), 'APP_DOC_FILENAME' => $sFilename, 'FOLDER_UID' => '', 'APP_DOC_TAGS' => '' );
    $oAppDocument = new AppDocument ();
    $oAppDocument->create ($aFields);
  
}

$Generated = $oOutputDocument->generate ( $uidOuput, $DataOutPutDoc, $pathOutput, $sFilename, $aOD ['OUT_DOC_TEMPLATE'], ( boolean ) $aOD ['OUT_DOC_LANDSCAPE'], $aOD ['OUT_DOC_GENERATE'], $aProperties );
//$templateHTML=G::replaceDataField ( $aOD ['OUT_DOC_TEMPLATE'],$APP_DATA );
$templateHTML=G::replaceDataField ( $aOD ['OUT_DOC_TEMPLATE'],$APP_DATA );
$fileNamePDF=G::replaceDataField ( $aOD['OUT_DOC_FILENAME'],$APP_DATA );

########################################################################################################################################################

if (ini_get ( "pcre.backtrack_limit" ) < 1000000) {
  ini_set ( "pcre.backtrack_limit", 1000000 );
}
if (ini_get ( "memory_limit" ) < 256) {
  //ini_set ( 'memory_limit', '512M' );
  ini_set ( 'memory_limit', '-1' );  
}
@set_time_limit ( 10000 );

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();        
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        /*if (isset ( $_GET ['TypePolicy'] ) && $_GET ['TypePolicy'] == 'Draft') {
          $Server = "http://".$_SERVER['HTTP_HOST'];
          $DraftImage = $Server.'/plugin/vkhowden/WATERMARK.gif';
          $this->Image($DraftImage, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, 'midle', $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
          $this->SetAlpha(1);
        }*/
        
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
    
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
                
        // Title
        $this->Cell(30, 0, 'Expense Report - HSU -VKU ', 0, 0, 'L', 0, '');
       $this->SetFont('helvetica', 'I', 8);
       // Page number                
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
$pdf = new MYPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 10, 10, false);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// define some HTML content with style

$html = <<<EOF
$templateHTML
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

$NameToGenerate =$fileNamePDF;// $sFilename . "-" . $PolicyNR;

//Close and output PDF document
$pdf->Output($NameToGenerate.'.pdf', 'D');
?>
