<?php
header("Content-Type: text/html;charset=utf-8");
//seleccion de zona local
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

//Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', '1');

require ('../../../docs/plugins/fpdf/fpdf.php');
require('conexion.php');

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
	$wmax=($w-2*$this->cMargin)*1200/$this->FontSize;
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
	$provincia = $_GET['provincia'];
	if($provincia == '29') {
		$provTit = "Málaga";
	} elseif($provincia == '11') {
		$provTit = "Cádiz";	
	} elseif($provincia == '41') {
		$provTit = "Sevilla";
	} elseif($provincia == '21') {
		$provTit = "Huelva";
	} elseif($provincia == '14') {
		$provTit = "Córdoba";
	} elseif($provincia == '52') {
		$provTit = "Melilla";				
	} else {
		$provTit = "Sin Provincia";
	}
	$recu = $_GET['selRecu'];
	if($recu == '1') { //Ambu
	  $recuTit = "ambulancia";	  
	} elseif($recu == '2') { //Ruta
	  $recuTit = "Ruta";
	} elseif($recu == '3') { //Due
	  $recuTit = "enfermería";	  
	} elseif($recu == '4') { //V_M
	  $recuTit = "visitas médicas";	  
	} elseif($recu == '5') { //C_TLF
	  $recuTit = "consulta tlf.";	  
	} elseif($recu == '6') { //SEG_MED
	  $recuTit = "seg. médico";	  //Queda por especificar como realizarlo 
	} else {
	  $recuTit = "no reconocido";	  
	}
	$asgNom = $_GET['asgNom'];
	
	if($provincia == '41' && $asgNom == 'ASISA') {
		$raDele = $_GET['raDele'];
		if($raDele == '1') { // RAD
			$titDele = " RAD";
		} elseif($raDele == '2') {
			$titDele = " Delegación";
		} else {
			$titDele = "";
		}
	} else {
		$titDele = "";
	}
	
	$this->Image($_SERVER['DOCUMENT_ROOT'].'/ops/img/logo_amba.png',5,4,40);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    //$this->Cell(100);
    // T�tulo
    $this->Cell(0,4,' '.utf8_decode($asgNom).' - '.utf8_decode($recuTit).' - '.utf8_decode($provTit).' - '.utf8_decode($titDele),0,0,'C');
    // Salto de l�nea
    $this->Ln(8);
	$this->SetMargins(4,10,4);
	$this->Ln(0);
	
	$this->SetWidths(array(14, 60, 28, 20, 19, 8, 10, 9, 8, 35, 35, 18, 25));
	$this->SetFont('Arial','B',8);
	$this->SetFillColor(0,0,0);
    $this->SetTextColor(255);

		for($i=0;$i<1;$i++)
			{	
				$this->Row(array('Fecha', 'Nombre', 'Poliza', 'Autorizacion', 'Recurso', 'id/vt', 'Med.', 'Due', 'F/n', 'Recoger', 'Trasladar', 'Km', 'Tipo'));
			}	
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
	$this->Cell(0,10,'Ambulancias Andalucia S.Coop.And. | C/La Boheme 29, Planta 1 C.P. 29006 | tlf.951 204 280 / 902 750 688 fax 952 243 428',0,0,'C');

}

}
	$pdf=new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->Open();
	$pdf->AddPage();

$con = new DB;	
$listado = $con->conectar();
/* VARIABLES */
if(isset($_GET['diaIni'])) {
  $diaIni = $_GET['diaIni'];
} else {
  $diaIni = date('Y-m-d');
}

if(isset($_GET['diaFin'])) {
  $diaFin = $_GET['diaFin'];
} else {
  $hoy = date('Y-m-d');
  $diaFin = strtotime('+1 day', strtotime($hoy));
  $diaFin = date('Y-m-d', $diaFin);
}
if(isset($_GET['aseguradora'])) {
  $aSelc = $_GET['aseguradora'];
} else {
  $aSelc = '0';
}
if(isset($_GET['provincia'])) {
  $selProv = $_GET['provincia'];
} else {
  $selProv = '0';
}

if(isset($_GET['selRecu'])) {
  $selRecu = $_GET['selRecu'];
  if($selRecu == '1') { //Ambu
	  $codRecu = "'1','3','5'";	  
  } elseif($selRecu == '2') { //Ruta
	  $codRecu = "'7'";
  } elseif($selRecu == '3') { //Due
	  $codRecu = "'2'";	  
  } elseif($selRecu == '4') { //V_M
	  $codRecu = "'4'";	  
  } elseif($selRecu == '5') { //C_TLF
	  $codRecu = "'6'";	  
  } elseif($selRecu == '6') { //SEG_MED
	  $codRecu = "'4'";	  //Queda por especificar como realizarlo 
  } else {
	  $codRecu = "'0'";	  
  }	  
} else {
  $selRecu = '0';
  $codRecu = "'0'";	  
}
if(isset($_GET['delFac'])) {
  $selDele = $_GET['delFac'];
} else {
  $selDele = '0';
}
if(isset($_GET['mostCont'])) { //0 no mostrar cont. - 1 mostrar cont
  $seleCont = $_GET['mostCont'];
} else {
  $seleCont = '0';
}
if(isset($_GET['raDele'])) { // Asisa - RAD / Delegación
	$asisaSe = $_GET['raDele'];
} else {
	$asisaSe = '0';
}

/* CONSULTA PARA LISTADOS */
if($selRecu == '1') {
	/* AMBULANCIA */
	if($selProv == '29') { // Ambulancias - Málaga
		if($aSelc == '1') { // Asisa
			if($selDele == 0) { //Del.Actual
				if($seleCont == '0') { //No mostrar continuados
					/* Málaga-Asisa-Del.actual-No cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion !='53'
						ORDER BY servicio.fecha, servicio.nombre ASC ";
				} else { //Mostrar continuados
					/* Málaga-Asisa-Del.actual-Si Cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.delegacion !='53'
						ORDER BY servicio.fecha, servicio.nombre ASC ";
				}
			} else { //Del. Distinta
				if($seleCont == '0') { //No mostrar continuados
					/* Málaga-Asisa-Del.distinta-No cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion ='53'
						ORDER BY servicio.fecha, servicio.nombre ASC ";
				} else { //Mostrar continuados
					/* Málaga-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.delegacion ='53'
						ORDER BY servicio.fecha, servicio.nombre ASC ";				
				}			
			}
		} elseif($aSelc == '2') { //Adeslas
			if($selDele == 0) { //Del.Actual
				if($seleCont == '0') { //No mostrar continuados
					/* Málaga-Adeslas-Del.actual-No cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0','29')
						ORDER BY servicio.fecha, servicio.nombre ASC ";				
				} else { //Mostrar continuados
					/* Málaga-Adeslas-Del.actual-Si Cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.delegacion IN('0','29')
						ORDER BY servicio.fecha, servicio.nombre ASC ";				
				}
			} else { //Del. Distinta
				if($seleCont == '0') { //No mostrar continuados
					/* Málaga-Adeslas-Del.distinta-No cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0','29')
						ORDER BY servicio.fecha, servicio.nombre ASC ";
				} else { //Mostrar continuados
					/* Málaga-Adeslas-Del.distinta-Si Cont. */
					$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0','29')
						ORDER BY servicio.fecha, servicio.nombre ASC ";
				}			
			}	
		} else { //Otras
			if($seleCont == '0') { //No mostrar continuados
				/* Málaga-Otras-Not cont. */
				$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
					ORDER BY servicio.fecha, servicio.nombre ASC ";			
			} else { //Mostrar continuados
				/* Málaga-Otras-Si Cont. */
				$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv'
					ORDER BY servicio.fecha, servicio.nombre ASC ";
			}			
		}
	} elseif($selProv == '41') { //Ambulancias - Sevilla
		if($aSelc == '1') { // Asisa
			if($asisaSe == '1') {
				if($selDele == 0) { //Del.Actual
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.actual-No cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41') AND servicio.coDemanda IS NOT NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.actual-Si Cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					}
				} else { //Del. Distinta
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.distinta-No cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";				
					}			
				}				
			} elseif($asisaSe == '2') { // Asisa Delegación
				if($selDele == 0) { //Del.Actual
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.actual-No cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41') AND servicio.coDemanda IS NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.actual-Si Cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					}
				} else { //Del. Distinta
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.distinta-No cont. */
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
						$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
							ORDER BY servicio.fecha, servicio.nombre ASC ";				
					}			
				}			
			} else {
				if($selDele == 0) { //Del.Actual
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.actual-No cont. */
						$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41')
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.actual-Si Cont. */
						$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41')
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					}
				} else { //Del. Distinta
					if($seleCont == '0') { //No mostrar continuados
						/* Sevilla-Asisa-Del.distinta-No cont. */
						$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41')
							ORDER BY servicio.fecha, servicio.nombre ASC ";
					} else { //Mostrar continuados
						/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
						$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41')
							ORDER BY servicio.fecha, servicio.nombre ASC ";				
					}			
				}			
			}
		} else {
			if($seleCont == '0') { //No mostrar continuados
				/* Sevilla-Otras-Not cont. */
				$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
					ORDER BY servicio.fecha, servicio.nombre ASC ";			
			} else { //Mostrar continuados
				/* Sevilla-Otras-Si Cont. */
				$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv'
					ORDER BY servicio.fecha, servicio.nombre ASC ";
			}		
		}
	} else { // Ambulancias - Otras
		if($seleCont == '0') { //No mostrar continuados
			/* Sevilla-Otras-Not cont. */
			$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
				ORDER BY servicio.fecha, servicio.nombre ASC ";			
		} else { //Mostrar continuados
			/* Sevilla-Otras-Si Cont. */
			$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv'
				ORDER BY servicio.fecha, servicio.nombre ASC ";
		}
	}
/*	
} elseif($selRecu == '2') {
} elseif($selRecu == '3') {
} elseif($selRecu == '5') {
*/
} elseif($selRecu == '4') {
	/* VISITA MEDICA */
	# Visitas médicas Sevilla - RAD / Delegación
	if($selProv == '41' AND $aSelc == '1') { // Filtro para Sevilla y Asisa
		if($asisaSe == '1') { //RAD
			$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					servicio.coDemanda, cia.idCia, cia.ciaNom, 
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.tipo ='2' AND servicio.coDemanda IS NOT NULL
				ORDER BY servicio.fecha, servicio.nombre ASC ";			
		} elseif($asisaSe == '2') { //Delegación
			$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					servicio.coDemanda, cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.tipo ='2' AND servicio.coDemanda IS NULL
				ORDER BY servicio.fecha, servicio.nombre ASC ";			
		} else { //Todos
			$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.tipo ='2'
				ORDER BY servicio.fecha, servicio.nombre ASC ";		
		}
	} else {
		$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
				servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
				cia.idCia, cia.ciaNom,
				recurso.idRecu, recurso.recuCorto,
				servi.idServi, servi.nomSer, servi.icono
			FROM servicio
				LEFT JOIN cia ON servicio.idCia = cia.idCia
				LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
				LEFT JOIN servi ON servicio.tipo = servi.idServi
			WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
					AND servicio.provincia = '$selProv' AND servicio.tipo ='2'
			ORDER BY servicio.fecha, servicio.nombre ASC ";		
	}
} elseif($selRecu == '6') {	
	/* SEG. MEDICO */
	$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
			servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
			cia.idCia, cia.ciaNom,
			recurso.idRecu, recurso.recuCorto,
			servi.idServi, servi.nomSer, servi.icono
		FROM servicio
			LEFT JOIN cia ON servicio.idCia = cia.idCia
			LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
			LEFT JOIN servi ON servicio.tipo = servi.idServi
		WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
				AND servicio.provincia = '$selProv' AND servicio.tipo ='9'
		ORDER BY servicio.fecha, servicio.nombre ASC ";	
} else {
	/* OTRO - TODAS */
	# Enfermería - Asisa Sevilla - RAD / Delegación
	if($selProv == '41' AND $aSelc == '1') { // Filtro para Sevilla y Asisa
		if($asisaSe == '1') { //RAD
			$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					servicio.coDemanda, cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
						AND servicio.provincia = '$selProv' AND servicio.coDemanda IS NOT NULL
				ORDER BY servicio.fecha, servicio.nombre ASC ";
			//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'			
		} elseif($asisaSe == '2') { // Delegación
			$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					servicio.coDemanda, cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
						AND servicio.provincia = '$selProv' AND servicio.coDemanda IS NULL
				ORDER BY servicio.fecha, servicio.nombre ASC ";
			//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'				
		} else { //Todo
			$sqlCons = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
						AND servicio.provincia = '$selProv'
				ORDER BY servicio.fecha, servicio.nombre ASC ";
			//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'			
		}
	} else {	
		$sqlCons =  "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
				servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ,
				cia.idCia, cia.ciaNom,
				recurso.idRecu, recurso.recuCorto,
				servi.idServi, servi.nomSer, servi.icono
			FROM servicio
				LEFT JOIN cia ON servicio.idCia = cia.idCia
				LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
				LEFT JOIN servi ON servicio.tipo = servi.idServi
			WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.provincia = '$selProv' AND servicio.estServ NOT IN('15','16','17') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
			ORDER BY servicio.fecha, servicio.nombre ASC ";
		//AND servicio.provincia = '29' AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'
		//AND servicio.provincia = '29' AND servicio.locRec = 'MALAGA'
	}
}
//array_map("utf8_decode", $header);

	$listado = mysql_query($sqlCons);
	$numfilas = mysql_num_rows($listado);

	for ($i=0; $i<$numfilas; $i++)
		{
			$fila = mysql_fetch_array($listado);
			$pdf->SetFont('Arial','',6);
			
			$nFecha   = date_create($fila['fecha']);
			$fechaFor = date_format($nFecha,'d-m-y');
			if($fila['fest'] == '1') { $fest = "SI"; } else { $fest = ""; }
			if($fila['enfermero'] == '1') { $due = "SI"; } else { $due = ""; }
			if($fila['medico'] == '1') { $med = "SI"; } else { $med = ""; }
			if($fila['idvta'] == '1') { $idvta = "SI"; } else { $idvta = ""; }
			
			if($i%2 == 1)
			{
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->Row(array($fechaFor, $fila['nombre']." ".$fila['apellidos'], $fila['poliza'], $fila['autorizacion'], $fila['recuCorto'], $idvta, $med, $due, $fest, $fila['locRec'], $fila['locTras'], $fila['km'], $fila['nomSer']));
			}
			else
			{
				$pdf->SetFillColor(224,235,255);
    			$pdf->SetTextColor(0);
				$pdf->Row(array($fechaFor, $fila['nombre']." ".$fila['apellidos'], $fila['poliza'], $fila['autorizacion'], $fila['recuCorto'], $idvta, $med, $due, $fest, $fila['locRec'], $fila['locTras'], $fila['km'], $fila['nomSer']));
			}
		}
$pdf->Output();
?>
<?php
mysql_free_result($listado);
?>