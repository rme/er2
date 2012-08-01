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

if (ini_get ( "pcre.backtrack_limit" ) < 1000000) 
{
  ini_set ( "pcre.backtrack_limit", 1000000 );
}
if (ini_get ( "memory_limit" ) < 256) 
{
  ini_set ( 'memory_limit', '512M' );
}

@set_time_limit ( 10000 );
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
$DataOutPutDoc = array (); 

if ($uidOuput == '9449757405009ddbd08eec0023591226') { // Expense Report Filer
    

    if (isset ( $Fields ['APP_DATA'] ['EXPENSE_REPORT_NAME'] ))
      $DataOutPutDoc ['EXPENSE_REPORT_NAME'] = $Fields ['APP_DATA'] ['EXPENSE_REPORT_NAME'];
    if (isset ( $Fields ['APP_DATA'] ['repUserName'] ))
      $DataOutPutDoc ['repUserName'] = $Fields ['APP_DATA'] ['repUserName'];

} // End OutPut
















//G::replaceDataField ( $aOD ['OUT_DOC_FILENAME'], $Fields ['APP_DATA'] )



#################################################################################################################
$vars ['body'] = "<html>";
$vars ['body'] .= "<title></title>";
$vars ['body'] .= "<body>";
$vars ['body'] .= '<br><br>';
$vars ['body'] .= '<p align="center"><table width="100%" cellspacing = "50" border="1">';
$vars ['body'] .= '<tr>';
$vars ['body'] .= '<td align="center">dddd</td>';
$vars ['body'] .= '<td align="center">fgfg</td>';
$vars ['body'] .= '</tr>';    
$vars ['body'] .= '</table></p>'; 

$htmlFinal = $vars ['body'];
##################################################################################################################
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
        $this->Cell(30, 0, 'LIUIPCCP001-DO-CW-0709 ', 0, 0, 'L', 0, '');
       $this->SetFont('helvetica', 'I', 8);
        // Title
        $this->Cell(100, 0, 'Liberty Insurance Underwriters, Inc.', 0, 0, 'C', 0, '');       
       $this->SetFont('helvetica', 'I', 8);
       // Page number                
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
################################################################################################################################33
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(10, 10, 10, false);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->SetFont('helvetica', '', 10);


$pdf->AddPage();

// define some HTML content with style

$html = <<<EOF
$htmlFinal
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

$NameToGenerate = $sFilename . "-" . $PolicyNR;

//Close and output PDF document
$pdf->Output($NameToGenerate.'.pdf', 'I');

