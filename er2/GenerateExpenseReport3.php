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

####################################################################### Parameters ###########################################################33
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
######################################################################## Grid Report ####################################################333
$cadena='';
$i=0;
$GRID=$APP_DATA ['REPORT_GRID'];
foreach($GRID as $row)
{
 $i++;
 $cadena=$cadena.'<tr><td class="FormCellTable"><font size="2">'.$i.'</font></td>';
 foreach($row as $key => $dato) {
      if(strpos($key,"label")==false)
        $cadena=$cadena.'<td class="FormCellTable"><font size="2">'.$dato.'</font></td>';
 }
 $cadena=$cadena.'</tr>';
}
$APP_DATA['GRID_TABLE'] = $cadena;
$htmlFinal='
<style type="text/css">
#main {
    background-color:#FFF; 
    color: #808080;
    font: 10px helvetica,Tahoma,MiscFixed;
    margin: 0;
}
#grid {
  font: 9px helvetica,Tahoma,MiscFixed !important;
}
.FormTitle {
    background-color: #E0E7EF;
    border-bottom: 1px solid #C3D1DF;
    color: #0000AE;
    font-weight: bold;
    text-shadow: 0 1px 0 #FFFFFF;
}
.FormTitleMain {
    background-color: #E0E7EF;
    border-bottom: 1px solid #C3D1DF;
    padding-top:2px;
    color: #0000AE;
    font-weight: bold;
    text-align: center;
    font: 20px helvetica,Tahoma,MiscFixed;
}
.FormTitleTable {
    background-color: #E0E7EF;
    border-bottom: 1px solid #C3D1DF;
    padding-top:2px;
    color: #000;
    font-weight: bold;
    text-align: center;
    font: 9px helvetica,Tahoma,MiscFixed;
}
.FormCellTable {
    background-color: #FFF;
    border-bottom: 1px solid #C3D1DF;
    color: #000;
    text-align: center;
    font: 9px helvetica,Tahoma,MiscFixed;
}
.FormLabel {
    color: #808080;
    padding-right: 5px;
    text-align: right;
}
.FormFieldContent {
    color: #000000;
    text-align: left;
}

</style>
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="FormTitleMain" colspan="2">
      @#EXPENSE_REPORT_NAME
    </td>
  </tr>
  <tr>
    <td class="FormLabel" width="20%">
      Employee:
    </td>
    <td class="FormFieldContent" width="80%">
      @#repUserName
    </td>
  </tr>
  
  <tr>
    <td class="FormLabel">
      Date:
    </td>
    <td class="FormFieldContent">
      @#payDateFiler
    </td>
  </tr>
  <tr>
    <td class="FormTitle" colspan="2">
      DETAILS OF EXPENSES
    </td>
  </tr>
</table>
<table  border="0" cellspacing="3" cellpadding="0" width="100%">
<tr>  
  <td class="FormTitleTable">
  <font size="2">No.</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Date</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Description</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Codes</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Payee</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Client</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Explanation</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Destination</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Amount USD</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Currency</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Exchange Rate</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Receipt</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">GL#</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Description Totals</font>
  </td>
  <td class="FormTitleTable">
  <font size="2">Month if Pre-pay</font>
  </td>
</tr>
@#GRID_TABLE
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="FormLabel">
      Total:
    </td>
    <td class="FormFieldContent">
      @#payAmountFiler
    </td>
  </tr>
</table>
</div>';

$htmlFinal=G::replaceDataField ( $htmlFinal,$APP_DATA );
#######################################################################################################################################################

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
$htmlFinal
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
