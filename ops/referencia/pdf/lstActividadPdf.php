<?php
header("Content-Type: text/html;charset=utf-8");
//seleccion de zona local
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');
//calculo de fechas

require ('../../../docs/plugins/fpdf/fpdf.php');
require('conexion.php');

function fechaFmt($fechaDada) {
  $fecha = new DateTime($fechaDada);
  $arregloFecha = date_format($fecha, 'd-m-Y');
  return $arregloFecha;
}

class PDF extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		
		$this->Rect($x,$y,$w,$h);

		$this->MultiCell($w,5,$data[$i],0,$a,'true');
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

// Cabecera de p�gina
function Header()
{
	$provincia = $_GET['selProv'];
	if($provincia == '29') {
		$provTit = "Málaga";
	} elseif($provincia == '11') {
		$provTit = "Cádiz";	
	} elseif($provincia == '41') {
		$provTit = "Sevilla";
	} else {
		$provTit = "Sin Provincia";
	}	
	$diaIni  = $_GET['diaIni'];
	$diaFin  = $_GET['diaFin'];
	//Aseguradora
	$aSelc   = $_GET['aSelc'];
	
	$con = new DB;	
	$aseg = $con->conectar();
	
	$straseg = "SELECT idCia, ciaNom FROM cia WHERE idCia = '$aSelc'";
	$aseg = mysqli_query($straseg);
	$naseg = mysqli_num_rows($aseg);
	$row_aseg = mysqli_fetch_array($aseg);
	
	$comp = $row_aseg['aseguradora'];
    // Logo
    $this->Image($_SERVER['DOCUMENT_ROOT'].'/ops/img/logo_amba.png',5,4,40);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    //$this->Cell(100);
    // T�tulo
    $this->Cell(0,4,'Resumen de actividad de '.$comp.' en '.$provTit.' desde '.fechaFmt($diaIni).' a '.fechaFmt($diaFin) ,0,0,'C');
    // Salto de l�nea
    $this->Ln(8);
}

// Pie de p�gina
function Footer()
{
    // Posici�n: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // N�mero de p�gina
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
	$this->Ln(0);
	$this->Cell(0,10,'Ambulancias Andalucia S.Coop.And. | C/La Boheme 29 Planta 1 P.E.San Alameda C.P. 29006 Malaga | tlf.951 204 280 / 902 750 688 fax 952 243 428',0,0,'C');
	$this->Ln(4);
	//$this->Cell(0,10,'Leyenda de servicios derivados: *-V: visita medica / *-A: ambulancia / *-V: UVI / *-E: enfermeria',0,0,'C');
}

}

	$pdf=new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(4,10,4);
	$pdf->Ln(2);
	
//AMBULANCIAS URGENTES URBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Ambulancias urgentes urbanas',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 22, 12, 15, 14, 8, 8, 8, 34, 34, 34, 15, 9, 9));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Recurso', 'id/vt', 'Med.', 'Due', 'Recoger', 'Destino', 'Trasladar', 'Tipo', 'Inicio', 'Fin'));
			}
$con = new DB;	
$amburg = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$stramburg = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.continuado, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.recurso IN('1', '3') AND servicio.provincia = '$provincia' AND servicio.continuado = '0' AND servicio.estServ != '15' AND servicio.locRec = '$provSel' AND servicio.locTras = '$provSel' AND servicio.tipo = '1' ORDER BY servicio.fecha ASC, servicio.nombre ASC";

//array_map("utf8_decode", $header);

	$amburg = mysql_query($stramburg);
	$n_amburg = mysql_num_rows($amburg);

	for ($i=0; $i<$n_amburg; $i++)
		{
			$famburg = mysql_fetch_array($amburg);
			$pdf->SetFont('Arial','',7);
				
				if($famburg['nomSer'] == "VISITA MEDICA") {
					$tipo = "V_M";
				} elseif($famburg['nomSer'] == "C_TELEFONICA") {
					$tipo = "C_TLF";
				} elseif($fambprg['nomSer'] == "RADIOTERAPIA") {
					$tipo = "RADIO.";					
				} else {
					$tipo = $famburg['nomSer'];
				}
				
				if($famburg['idvta'] == '') {
					$idvta = "";
				} elseif($famburg['idvta'] == '2') {
					$idvta = "";
				} else {
					$idvta = "SI";
				}
				
				if($famburg['medico'] == '1') {
					$medico = "SI";
				} else {
					$medico = "";
				}
				if($famburg['enfermero'] == '1') {
					$enfer = "SI";
				} else {
					$enfer = "";
				}
				
				if($famburg['recurso'] == "C_TELEFONICA") {
					$recurso = "C_TLF";
				} elseif($famburg['recurso'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $famburg['recuCorto'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($famburg['fecha'], $famburg['hllamada'], $famburg['paciente'], $famburg['poliza'], $famburg['autorizacion'], $famburg['DNIPac'], $recurso, $idvta, $medico, $enfer, $famburg['locRec'], $famburg['trasladar'], $famburg['locTras'], $tipo,date('H:i', strtotime($famburg['idReco'])), date('H:i', strtotime($famburg['idFin']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($famburg['fecha'], $famburg['hllamada'], $famburg['paciente'], $famburg['poliza'], $famburg['autorizacion'], $famburg['DNIPac'], $recurso, $idvta, $medico, $enfer, $famburg['locRec'], $famburg['trasladar'], $famburg['locTras'], $tipo, date('H:i', strtotime($famburg['idReco'])), date('H:i', strtotime($famburg['idFin']))));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_amburg,0);
	$pdf->Ln(5);	

//AMBULANCIAS URGENTES INTERURBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Ambulancias urgentes interurbanas',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 14, 8, 8, 8, 34, 34, 34, 15, 9, 9));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'id/vt', 'Med.', 'Due', 'Recoger', 'Destino', 'Trasladar', 'Tipo', 'Inicio', 'Fin'));
			}
$con = new DB;	
$amburgnurb = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}
//Ambulancias interubanas urgentes
$stramburgnurb = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.continuado, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.recurso IN('1', '3') AND servicio.provincia = '$provincia' AND servicio.continuado = '0' AND servicio.estServ != '15' AND (servicio.locRec != '$provSel' OR servicio.locTras != '$provSel') AND servicio.tipo = '1' ORDER BY servicio.fecha ASC, servicio.nombre ASC";
	
	$amburgnurb = mysql_query($stramburgnurb);
	$n_amburgnurb = mysql_num_rows($amburgnurb);

	for ($i=0; $i<$n_amburgnurb; $i++)
		{
			$famburgnurb = mysql_fetch_array($amburgnurb);
			$pdf->SetFont('Arial','',7);
				
				if($famburgnurb['tipo'] == "VISITA MEDICA") {
					$tipo = "V_M";
				} elseif($famburgnurb['tipo'] == "C_TELEFONICA") {
					$tipo = "C_TLF";
				} else {
					$tipo = $famburgnurb['nomSer'];
				}
				
				if($famburgnurb['idvta'] == '') {
					$idvta = "";
				} elseif($famburgnurb['idvta'] == '2') {
					$idvta = "";
				} else {
					$idvta = "SI";
				}
				
				if($famburgnurb['medico'] == '1') {
					$medico = "SI";
				} else {
					$medico = "";
				}
				if($famburgnurb['enfermero'] == '1') {
					$enfer = "SI";
				} else {
					$enfer = "";
				}
				
				if($famburgnurb['recurso'] == "C_TELEFONICA") {
					$recurso = "C_TLF";
				} elseif($famburgnurb['recurso'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $famburgnurb['recuCorto'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($famburgnurb['fecha'], $famburgnurb['hllamada'], $famburgnurb['paciente'], $famburgnurb['poliza'], $famburgnurb['autorizacion'], $famburgnurb['DNIPac'], $recurso, $idvta, $medico, $enfer, $famburgnurb['locRec'], $famburgnurb['trasladar'], $famburgnurb['locTras'], $tipo,date('H:i', strtotime($famburgnurb['idReco'])), date('H:i', strtotime($famburgnurb['idFin']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($famburgnurb['fecha'], $famburgnurb['hllamada'], $famburgnurb['paciente'], $famburgnurb['poliza'], $famburgnurb['autorizacion'], $famburgnurb['DNIPac'], $recurso, $idvta, $medico, $enfer, $famburgnurb['locRec'], $famburgnurb['trasladar'], $famburgnurb['locTras'], $tipo, date('H:i', strtotime($famburgnurb['idReco'])), date('H:i', strtotime($famburgnurb['idFin']))));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_amburgnurb,0);
	$pdf->Ln(5);		

//AMBULANCIAS PROGRAMADAS URBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Ambulancias programadas urbanas',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(12, 9, 45, 21, 17, 14, 14, 8, 8, 8, 34, 34, 34, 15, 9, 9));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'id/vt', 'Med.', 'Due', 'Recoger', 'Destino', 'Trasladar', 'Tipo', 'Inicio', 'Fin'));
			}
$con = new DB;	
$ambprg = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}
//Ambulancias programadas urbanas
$strambprg = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.continuado, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.recurso IN('1', '3') AND servicio.provincia = '$provincia' AND servicio.estServ != '15' AND servicio.locRec = '$provSel' AND servicio.locTras = '$provSel' AND servicio.tipo IN('3','5','6','7','8','10','11','12','14','24') 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";

	$ambprg = mysql_query($strambprg);
	$n_ambprg = mysql_num_rows($ambprg);

	for ($i=0; $i<$n_ambprg; $i++)
		{
			$fambprg = mysql_fetch_array($ambprg);
			$pdf->SetFont('Arial','',7);
			
				if($fambprg['nomSer'] == "REHABILITACION") {
					$tipo = "RHB.";
				} elseif($fambprg['nomSer'] == "SECUNDARIO") {
					$tipo = "SECUN.";
				} elseif($fambprg['nomSer'] == "RADIOTERAPIA") {
					$tipo = "RADIO.";					
				} else {
					$tipo = $fambprg['nomSer'];
				}
				
				if($fambprg['idvta'] == '') {
					$idvta = "";
				} elseif($fambprg['idvta'] == '2') {
					$idvta = "";
				} else {
					$idvta = "SI";
				}
				
				if($fambprg['medico'] == '1') {
					$medico = "SI";
				} else {
					$medico = "";
				}
				if($fambprg['enfermero'] == '1') {
					$enfer = "SI";
				} else {
					$enfer = "";
				}
				
				if($fambprg['recurso'] == "C_TELEFONICA") {
					$recurso = "C_TLF";
				} elseif($fambprg['recurso'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $fambprg['recuCorto'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fambprg['fecha'], $fambprg['hllamada'], $fambprg['paciente'], $fambprg['poliza'], $fambprg['autorizacion'], $fambprg['DNIPac'], $recurso, $idvta, $medico, $enfer, $fambprg['locRec'], $fambprg['trasladar'], $fambprg['locTras'], $tipo,date('H:i', strtotime($fambprg['idReco'])), date('H:i', strtotime($fambprg['idFin']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fambprg['fecha'], $fambprg['hllamada'], $fambprg['paciente'], $fambprg['poliza'], $fambprg['autorizacion'], $fambprg['DNIPac'], $recurso, $idvta, $medico, $enfer, $fambprg['locRec'], $fambprg['trasladar'], $fambprg['locTras'], $tipo,date('H:i', strtotime($fambprg['idReco'])), date('H:i', strtotime($fambprg['idFin']))));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_ambprg,0);
	$pdf->Ln(5);		

//AMBULANCIAS PROGRAMADAS INTERURBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Ambulancias programadas interurbanas',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(12, 9, 45, 21, 17, 14, 14, 8, 8, 8, 34, 34, 34, 15, 9, 9));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'id/vt', 'Med.', 'Due', 'Recoger', 'Destino', 'Trasladar', 'Tipo', 'Inicio', 'Fin'));
			}
$con = new DB;	
$ambprgnurb = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$strambprgnurb = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.continuado, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.recurso IN('1', '3') AND servicio.provincia = '$provincia' AND servicio.estServ != '15' AND (servicio.locRec != '$provSel' OR servicio.locTras != '$provSel') AND servicio.tipo IN('3','5','6','7','8','10','11','12','14','24') 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";
//array_map("utf8_decode", $header);

	$ambprgnurb = mysql_query($strambprgnurb);
	$n_ambprgnurb = mysql_num_rows($ambprgnurb);

	for ($i=0; $i<$n_ambprgnurb; $i++)
		{
			$fambprgnurbnurb = mysql_fetch_array($ambprgnurb);
			$pdf->SetFont('Arial','',7);
			
				if($fambprgnurbnurb['nomSer'] == "REHABILITACION") {
					$tipo = "RHB.";
				} elseif($fambprgnurbnurb['nomSer'] == "SECUNDARIO") {
					$tipo = "SECUN.";
				} elseif($fambprgnurbnurb['nomSer'] == "RADIOTERAPIA") {
					$tipo = "RADIO.";				
				} else {
					$tipo = $fambprgnurbnurb['nomSer'];
				}
				
				if($fambprgnurbnurb['idvta'] == '') {
					$idvta = "";
				} elseif($fambprgnurbnurb['idvta'] == '2') {
					$idvta = "";
				} else {
					$idvta = "SI";
				}
				
				if($fambprgnurbnurb['medico'] == '1') {
					$medico = "SI";
				} else {
					$medico = "";
				}
				if($fambprgnurbnurb['enfermero'] == '1') {
					$enfer = "SI";
				} else {
					$enfer = "";
				}
				
				if($fambprgnurbnurb['recurso'] == "C_TELEFONICA") {
					$recurso = "C_TLF";
				} elseif($fambprgnurbnurb['recurso'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $fambprgnurbnurb['recuCorto'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fambprgnurbnurb['fecha'], $fambprgnurbnurb['hllamada'], $fambprgnurbnurb['paciente'], $fambprgnurbnurb['poliza'], $fambprgnurbnurb['autorizacion'], $fambprgnurbnurb['DNIPac'], $recurso, $idvta, $medico, $enfer, $fambprgnurbnurb['locRec'], $fambprgnurbnurb['trasladar'], $fambprgnurbnurb['locTras'], $tipo,date('H:i', strtotime($fambprgnurbnurb['idReco'])), date('H:i', strtotime($fambprgnurbnurb['idFin']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fambprgnurbnurb['fecha'], $fambprgnurbnurb['hllamada'], $fambprgnurbnurb['paciente'], $fambprgnurbnurb['poliza'], $fambprgnurbnurb['autorizacion'], $fambprgnurbnurb['DNIPac'], $recurso, $idvta, $medico, $enfer, $fambprgnurbnurb['locRec'], $fambprgnurbnurb['trasladar'], $fambprgnurbnurb['locTras'], $tipo,date('H:i', strtotime($fambprgnurbnurb['idReco'])), date('H:i', strtotime($fambprgnurbnurb['idFin']))));
			}			
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_ambprgnurb,0);
	$pdf->Ln(5);		

//VISITAS MEDICAS URBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Servicios medicos urbanos',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 14, 72, 37, 34, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$vmedica = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

//Visitas médicas urbanas

$strvmedica = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.locRec = '$provSel' AND servicio.tipo = '2' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";

	$vmedica = mysql_query($strvmedica);
	$n_vmedica = mysql_num_rows($vmedica);

	for ($i=0; $i<$n_vmedica; $i++)
		{
			$fvmedica = mysql_fetch_array($vmedica);
			$pdf->SetFont('Arial','',7);
			
				if($fvmedica['nomSer'] == "VISITA MEDICA") {
					$tipo = "V_M";
				} else {
					$tipo = $fvmedica['nomSer'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fvmedica['fecha'], $fvmedica['hllamada'], $fvmedica['paciente'], $fvmedica['poliza'], $fvmedica['autorizacion'], $fvmedica['DNIPac'], $recurso, $fvmedica['recoger'], $fvmedica['locRec'], $tipo, date('H:i', strtotime($fvmedica['idReco']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fvmedica['fecha'], $fvmedica['hllamada'],$fvmedica['paciente'], $fvmedica['poliza'], $fvmedica['autorizacion'], $fvmedica['DNIPac'], $recurso, $fvmedica['recoger'], $fvmedica['locRec'], $tipo, date('H:i', strtotime($fvmedica['idReco']))));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_vmedica,0);
	$pdf->Ln(5);

//VISITAS MEDICAS INTERURBANAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Servicios medicos interurbanos',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 14, 72, 37, 34, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$vmedicanurb = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$strvmedicanurb = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.locRec !='$provSel' AND servicio.tipo = '2' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";

	$vmedicanurb = mysql_query($strvmedicanurb);
	$n_vmedicanurb = mysql_num_rows($vmedicanurb);

	for ($i=0; $i<$n_vmedicanurb; $i++)
		{
			$fvmedicanurb = mysql_fetch_array($vmedicanurb);
			$pdf->SetFont('Arial','',7);
			
				if($fvmedicanurb['nomSer'] == "VISITA MEDICA") {
					$tipo = "V_M";
				} else {
					$tipo = $fvmedicanurb['nomSer'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fvmedicanurb['fecha'], $fvmedicanurb['hllamada'], $fvmedicanurb['paciente'], $fvmedicanurb['poliza'], $fvmedicanurb['autorizacion'], $fvmedicanurb['DNIPac'], $recurso, $fvmedicanurb['recoger'], $fvmedicanurb['locRec'], $tipo, date('H:i', strtotime($fvmedicanurb['idReco']))));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fvmedicanurb['fecha'], $fvmedicanurb['hllamada'], $fvmedicanurb['paciente'], $fvmedicanurb['poliza'], $fvmedicanurb['autorizacion'], $fvmedicanurb['DNIPac'], $recurso, $fvmedicanurb['recoger'], $fvmedicanurb['locRec'], $tipo, date('H:i', strtotime($fvmedicanurb['idReco']))));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_vmedicanurb,0);
	$pdf->Ln(5);

//CONSULTAS TELEFONICAS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Consultas telefonicas',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 14, 72, 37, 34, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$ctlf = $con->conectar();


//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}


$strctlf = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.locRec !='$provSel' AND servicio.recurso = '6' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";
//array_map("utf8_decode", $header);

	$ctlf = mysql_query($strctlf);
	$n_ctlf = mysql_num_rows($ctlf);

	for ($i=0; $i<$n_ctlf; $i++)
		{
			$fctlf = mysql_fetch_array($ctlf);
			$pdf->SetFont('Arial','',7);
			

				$tipo = $fctlf['recuCorto'];

				if($fctlf['recurso'] == "6") {
					$recurso = "C_TLF";			
				} else {
					$recurso = $fctlf['recurso'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fctlf['fecha'], $fctlf['hllamada'], $fctlf['paciente'], $fctlf['poliza'], $fctlf['autorizacion'], $fctlf['DNIPac'], $recurso, $fctlf['recoger'], $fvmedicanurb['locRec'], $tipo,''));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fctlf['fecha'], $fctlf['hllamada'], $fctlf['paciente'], $fctlf['poliza'], $fctlf['autorizacion'], $fctlf['DNIPac'], $recurso, $fctlf['recoger'], $fvmedicanurb['locRec'], $tipo, ''));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_ctlf,0);
	$pdf->Ln(5);
	

//SEGUIMIENTOS MEDICOS
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Seguimientos medicos',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 14, 72, 37, 34, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$segmed = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$strvsegmed = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.tipo = '9' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";


	$segmed = mysql_query($strvsegmed);
	$n_segmed = mysql_num_rows($segmed);

	for ($i=0; $i<$n_segmed; $i++)
		{
			$fsegmed = mysql_fetch_array($segmed);
			$pdf->SetFont('Arial','',7);

				$tipo = $fsegmed['recuCorto'];
				
				if($fsegmed['nomSer'] == "SEG_MEDICO") {
					$recurso = "SEG_MED";
				} elseif($fsegmed['nomSer'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $fsegmed['nomSer'];
				}

				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fsegmed['fecha'], '', $fsegmed['paciente'], $fvmedicanurb['poliza'], $fsegmed['autorizacion'], $fsegmed['DNIPac'], $recurso, $fsegmed['recoger'], $fsegmed['locRec'], $tipo, ''));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fsegmed['fecha'], '', $fsegmed['paciente'], $fvmedicanurb['poliza'], $fsegmed['autorizacion'], $fsegmed['DNIPac'], $recurso, $fsegmed['recoger'], $fsegmed['locRec'], $tipo, ''));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_segmed,0);
	$pdf->Ln(5);


//ENFERMERIA URBANA
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Enfermeria urbana',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 50, 24, 12, 15, 18, 72, 37, 25, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$dueurb = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$strdueurb = "SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.locRec = '$provSel' AND servicio.recurso = '2' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";

	$dueurb = mysql_query($strdueurb);
	$n_dueurb = mysql_num_rows($dueurb);

	for ($i=0; $i<$n_dueurb; $i++)
		{
			$fdueurb = mysql_fetch_array($dueurb);
			$pdf->SetFont('Arial','',7);
			
				$tipo = $fdueurb['recuCorto'];
				
				if($fdueurb['nomSer'] == "SEG_MEDICO") {
					$recurso = "SEG_MED";
				} elseif($fdueurb['nomSer'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $fdueurb['nomSer'];
				}

				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fdueurb['fecha'], $fdueurb['hllamada'], $fdueurb['paciente'], $fdueurb['poliza'], $fdueurb['autorizacion'], $fdueurb['DNIPac'], $recurso, $fdueurb['recoger'], $fdueurb['locRec'], $tipo, ''));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fdueurb['fecha'], $fdueurb['hllamada'], $fdueurb['paciente'], $fdueurb['poliza'], $fdueurb['autorizacion'], $fdueurb['DNIPac'], $recurso, $fdueurb['recoger'], $fdueurb['locRec'], $tipo, ''));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_dueurb,0);
	$pdf->Ln(5);

//ENFERMERIA INTERURBANA
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Enfermeria interurbana',0);
	$pdf->Ln(3);
	
	$pdf->SetWidths(array(13, 9, 45, 24, 12, 15, 18, 72, 37, 30, 15));
	$pdf->SetFont('Arial','B',7);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);


		for($i=0;$i<1;$i++)
			{	
				$pdf->Row(array('Fecha', 'Hora', 'Nombre', 'Poliza', 'ID', 'D.N.I.', 'Rescurso', 'Direccion', 'Localidad', 'Tipo', 'Inicio'));
			}
$con = new DB;	
$duenurb = $con->conectar();

//variables
$f_inicio = date("Y-m-d");
if (isset($_GET['diaIni'])) {
	$f_inicio = $_GET['diaIni'];
}
$f_fin = date("Y-m-d");
if (isset($_GET['diaFin'])) {
	$f_fin = $_GET['diaFin'];
}
if (isset($_GET['selProv'])) {
	$provincia = $_GET['selProv'];
}
if($provincia == 29) {
	$provSel = 'MALAGA';
} elseif($provincia == 41) {
	$provSel = 'SEVILLA';
} elseif($provincia == 11) {
	$provSel = 'ALGECIRAS';
} elseif($provincia == 52) {
	$provSel = 'MELILLA';
} elseif($provincia == 14) {
	$provSel = 'CORDOBA';
} elseif($provincia == 21) {
	$provSel = 'HUELVA';	
} else {
	$provSel = '0';
}
if (isset($_GET['ciaSel'])) {
	$aseguradora = $_GET['ciaSel'];
}

$strduenurb ="SELECT servicio.idSv, DATE_FORMAT(servicio.hora, '%H:%i') AS hllamada, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, 
	servicio.poliza, servicio.autorizacion, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.fest,servicio.medico,servicio.enfermero,servicio.idvta, CONCAT_WS(' ', servicio.nombre, servicio.apellidos) AS paciente,servicio.recoger, servicio.locRec, servicio.estServ, servicio.DNIPac,recurso.idRecu, recurso.recuCorto, servi.idServi, servi.nomSer, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$f_inicio' AND '$f_fin' AND servicio.idCia = '$aseguradora' AND servicio.provincia = '$provincia' AND servicio.estServ NOT IN('15','16') AND servicio.locRec != '$provSel' AND servicio.recurso = '2' 
	ORDER BY servicio.fecha ASC, servicio.nombre ASC";
//array_map("utf8_decode", $header);

	$duenurb = mysql_query($strduenurb);
	$n_duenurb = mysql_num_rows($duenurb);

	for ($i=0; $i<$n_duenurb; $i++)
		{
			$fduenurb = mysql_fetch_array($duenurb);
			$pdf->SetFont('Arial','',7);
			
				$tipo = $fduenurb['recuCorto'];
				
				if($fduenurb['nomSer'] == "SEG_MEDICO") {
					$recurso = "SEG_MED";
				} elseif($fduenurb['nomSer'] == "AMBULANCIA") {
					$recurso = "AMB";				
				} else {
					$recurso = $fduenurb['nomSer'];
				}
				
			if($i%2 == 1)
			{				
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fduenurb['fecha'], $fduenurb['hllamada'], $fduenurb['paciente'], $fduenurb['poliza'], $fduenurb['autorizacion'], $fduenurb['DNIPac'], $recurso, $fduenurb['recoger'], $fduenurb['locRec'], $tipo, ''));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',6);
				$pdf->Row(array($fduenurb['fecha'], $fduenurb['hllamada'], $fduenurb['paciente'], $fduenurb['poliza'], $fduenurb['autorizacion'], $fduenurb['DNIPac'], $recurso, $fduenurb['recoger'], $fduenurb['locRec'], $tipo, ''));
			}
		}
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','I',6);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,0,'Total de servicios: '.$n_duenurb,0);
	$pdf->Ln(5);


$pdf->Output();
?>
<?php
mysql_free_result($amburg);

mysql_free_result($amburgnurb);

mysql_free_result($ambprg);

mysql_free_result($vmedica);

mysql_free_result($vmedicanurb);

mysql_free_result($dueurb);

mysql_free_result($aseg);
?>
