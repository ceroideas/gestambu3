<?php
header("Content-Type: text/html;charset=utf-8");
//seleccion de zona local
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

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

// Cabecera de página
function Header()
{
	$provincia = $_GET['provincia'];
	$fecha = $_GET['fecha'];
  $recuSel = $_GET['recuSel'];
	//cambio de fecha
		list($anio,$mes,$dia)=explode("-",$fecha);
		$fecha_titulo="$dia-$mes-$anio";

	if($_GET['provincia'] == "29") {
		$encabezado = "MALAGA";
	} elseif($_GET['provincia'] == "11") {
		$encabezado = "CADIZ";
	} elseif($_GET['provincia'] == "41") {
		$encabezado = "SEVILLA";
	} elseif($_GET['provincia'] == "14") {
		$encabezado = "CORDOBA";
	} elseif($_GET['provincia'] == "21") {
		$encabezado = "HUELVA";
	} elseif($_GET['provincia'] == "52") {
		$encabezado = "MELILLA";			
	} else {
		echo "No se ha seleccionado provincia";
	}
    // Logo
    $this->Image($_SERVER['DOCUMENT_ROOT'].'/ops/img/logo_amba.png',5,4,40);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    //$this->Cell(100);
    // Título
    if($recuSel == 1 ) {
      $this->Cell(0,4,'Libreta de '.$encabezado.' dia '.$fecha_titulo,0,0,'C');
    } elseif($recuSel == 2 ){
      $this->Cell(0,4,'Enfermeria de '.$encabezado.' dia '.$fecha_titulo,0,0,'C');
    }

    // Salto de línea
    $this->Ln(8);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'R');
	$this->Ln(0);
	$this->Cell(0,10,'Ambulancias Andalucia S.Coop.And.|C/La Boheme 29 Planta 1 P.E. Alameda C.P. 29006|tlf.951 204 280/902 750 688 fax 952 243 428',0,0,'C');
}

}
//Variables
$f_ini = $_GET['fecha'];
$prov = $_GET['provincia'];
$recuSel = $_GET['recuSel'];

	$con = new DB;
	$pdf=new PDF('P','mm','A4');
	$pdf->AliasNbPages();
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(4,10,4);
	$pdf->Ln(0);

  if($recuSel==1){
	   $pdf->SetWidths(array(10, 17, 14, 19, 41, 20, 41, 18, 8, 14));
  } elseif($recuSel==2){
	   $pdf->SetWidths(array(6, 16, 14, 20, 53, 31, 60));
  } elseif($recuSel==7) {
	   $pdf->SetWidths(array(8, 16, 14, 12, 41, 20, 31, 18, 29, 13));
  }
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(0,0,0);
    $pdf->SetTextColor(255);

		for($i=0;$i<1;$i++) {
      if($recuSel==1){
  			$pdf->Row(array('Hora','Nombre', 'Tipo', '', 'Recoger', 'Loc.', 'Tras.', 'Loc.', 'i/v', 'Vehiculo'));
      } elseif($recuSel==2){
  			$pdf->Row(array('N','Tlf', 'Comp.', 'Nombre', 'Direccion', 'Localidad', 'Observaciones'));
      } elseif($recuSel==7){
  			$pdf->Row(array('H','Nombre', 'Tlf.', 'Tipo', 'Recoger', 'Loc.', 'Tras.', 'Loc.', 'Obs.', 'Vh.'));	  
	  }

		}
$con = new DB;
$listado = $con->conectar();
$cnsComp = $con->conectar();
$f_ini   = $_GET['fecha'];

$prov    = $_GET['provincia'];
/*if($prov == '41') {
	$prov = "'41','21'"; //se pide que no se agregen los servicios de huelva a los de sevilla se comenta este bloque
} else {
	$prov = "'".$prov."'";
}*/

$prov = "'".$prov."'";

$recuSel = $_GET['recuSel'];
//para ver ruta poner 7
if($recuSel == 1 ) {
  $strConsulta = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre, servicio.orden,
      servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda,
      vehiculo.idVh, vehiculo.matricula
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
    WHERE servicio.fecha = '$f_ini' AND servicio.provincia IN($prov) AND servicio.recurso IN('1', '3', '5') AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.orden, servicio.hora ASC
    ";
} elseif($recuSel == 2 ){
  $strConsulta = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.enfermero, servicio.nombre, servicio.orden, servicio.obs, servicio.tlf1, servicio.obs,
      servicio.recoger, servicio.locRec, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda, vehiculo.idVh, vehiculo.matricula
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
    WHERE servicio.fecha = '$f_ini' AND servicio.provincia IN($prov) AND servicio.recurso = '2' AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.orden, servicio.hora ASC
    ";
} elseif($recuSel == 7 ) {
  //RUTA
  $strConsulta = "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre, servicio.orden,servicio.obs,servicio.tlf1,
      servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda,
      vehiculo.idVh, vehiculo.matricula
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
    WHERE servicio.fecha = '$f_ini' AND servicio.provincia IN($prov) AND servicio.recurso = '7' AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.orden, servicio.hora ASC
    ";
}


//array_map("utf8_decode", $header);

	$listado = mysql_query($strConsulta);
	$numfilas = mysql_num_rows($listado);
  $ni = 1;

  for ($i=0; $i<$numfilas; $i++)
		{
			$pdf->SetFont('Arial','',6);
			$fila = mysql_fetch_array($listado);
      if($recuSel == 1 || $recuSel == 7) {
        if($fila['recurso'] == "1") {
  				if($fila['enfermero'] =="1" AND $fila['medico'] == "") {
  					$recurso ="AMB+DUE";
  				} elseif($fila['enfermero'] =="1" AND $fila['medico'] == "1") {
  					$recurso ="AMB+DUE+MED";
  				} elseif($fila['enfermero'] =="" AND $fila['medico'] == "1") {
  					$recurso ="AMB+MED";
  				} else {
  					$recurso ="AMB";
  				}
  			}
  			elseif($fila['recurso']=="5") {
  					$recurso="TAXI";
   			}
				elseif($fila['recurso']=="7") {
  					$recurso="RUTA";
   			}
				else {
  				if($fila['enfermero'] =="1" AND $fila['medico'] == "") {
  					$recurso ="UVI+DUE";
  				} elseif($fila['enfermero'] =="1" AND $fila['medico'] == "1") {
  					$recurso ="UVI+DUE+MED";
  				} elseif($fila['enfermero'] =="" AND $fila['medico'] == "1") {
  					$recurso ="UVI+MED";
  				} else {
  					$recurso ="UVI";
  				}
  			}

  			$anotacion =" ";
  			if($fila['idvta'] == "1") {
  				$idvta = "VTA";
  			} else { $idvta = " "; }


  			if($fila['tipo'] == "7") {
  				$tipo = "RHB";
  			} elseif($fila['tipo'] == "6") {
  				$tipo ="RADIO";
  			} elseif($fila['tipo'] == "8") {
  				$tipo ="LOGOP.";
  			} elseif($fila['tipo'] == "12") {
  				$tipo = "AEROP.";
  			} elseif($fila['tipo'] == "10") {
  				$tipo ="SECUND.";
  			} elseif($fila['tipo'] == "3") {
  				$tipo ="CONSLT.";
        } elseif($fila['tipo'] == "23") {
  				$tipo ="PREVENT.";
        } else {
  				$tipo = $fila['nomSer'];
  			}
      }
		/* ULTIMA SESION */
		$codConti = $fila['continuado'];
		
		$sqlComp = "SELECT continuado, estServ FROM servicio WHERE continuado='$codConti' AND estServ NOT IN('10','14','15')";
		$cnsComp = mysql_query($sqlComp);
		$numComp = mysql_num_rows($cnsComp);
			
		if($codConti != '0') {	
			if($numComp == '1') {
				$selUlti = 1;
				$ulText = "ULTIMA";
				$obsText = "ULTIMA, RENOVAR??";
				$ultAmb = "ULTIMA";
			} else {
				$selUlti = 0;
				$ulText = $fila['ciaNom'];			
				$obsText = "";
				$ultAmb = $recurso;				
			}
		} else {
			$selUlti = 0;
			$ulText = $fila['ciaNom'];
			$ultAmb = $recurso;
			$obsText = "";						
		}

      if($recuSel == 1) {
        //Ambulancia
        if($i%2 == 1)
  			{
  				$pdf->SetFillColor(255,255,255);
      			$pdf->SetTextColor(0);
  				$pdf->Row(array(substr($fila['hora'],0,5), substr(utf8_decode($fila['nombre']),0 , 9), $tipo, $ultAmb, substr(utf8_decode($fila['recoger']),0 ,29), utf8_decode($fila['locRec']), substr(utf8_decode($fila['trasladar']),0,29), utf8_decode($fila['locTras']), $idvta, $fila['matricula']));
  			}
  			else
  			{
  				$pdf->SetFillColor(224,235,255);
      			$pdf->SetTextColor(0);
  				$pdf->Row(array(substr($fila['hora'],0,5), substr(utf8_decode($fila['nombre']),0 , 9), $tipo, $ultAmb, substr(utf8_decode($fila['recoger']),0,29), utf8_decode($fila['locRec']), substr(utf8_decode($fila['trasladar']),0,29), utf8_decode($fila['locTras']), $idvta, $fila['matricula']));
  			}
      } elseif($recuSel == 2) {
        //Enfermero

        if($i%2 == 1)
  			{
  				$pdf->SetFillColor(255,255,255);
      		$pdf->SetTextColor(0);
  				$pdf->Row(array($ni++, $fila['tlf1'], $ulText, utf8_decode($fila['nombre']), utf8_decode($fila['recoger']), utf8_decode($fila['locRec']), utf8_decode($fila['obs']." ".$obsText)));
  			}
  			else
  			{
  				$pdf->SetFillColor(224,235,255);
      		$pdf->SetTextColor(0);
  				$pdf->Row(array($ni++, $fila['tlf1'], $ulText, utf8_decode($fila['nombre']), utf8_decode($fila['recoger']), utf8_decode($fila['locRec']), utf8_decode($fila['obs']." ".$obsText)));
  			}
      } elseif($recuSel == 7) {
        //Ambulancia
        if($i%2 == 1)
  			{
  				$pdf->SetFillColor(255,255,255);
      			$pdf->SetTextColor(0);
  				$pdf->Row(array(substr($fila['hora'],0,5), substr(utf8_decode($fila['nombre']),0 , 9), $fila['tlf1'], $tipo, substr(utf8_decode($fila['recoger']),0 ,29), utf8_decode($fila['locRec']), substr(utf8_decode($fila['trasladar']),0,29), utf8_decode($fila['locTras']), $fila['obs'], $fila['matricula']));
  			}
  			else
  			{
  				$pdf->SetFillColor(224,235,255);
      			$pdf->SetTextColor(0);
  				$pdf->Row(array(substr($fila['hora'],0,5), substr(utf8_decode($fila['nombre']),0 , 9), $fila['tlf1'], $tipo, substr(utf8_decode($fila['recoger']),0,29), utf8_decode($fila['locRec']), substr(utf8_decode($fila['trasladar']),0,29), utf8_decode($fila['locTras']), $fila['obs'], $fila['matricula']));
  			}	  
	  }

		}

$pdf->Output();
?>
<?php
mysql_free_result($listado);
?>
