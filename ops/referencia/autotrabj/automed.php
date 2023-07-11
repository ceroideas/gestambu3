<?php
    include '../../../functions/function.php';

    /*
    idCate	cate
    1	Administrador
    2	Coordinación
    3	Operaciones
    4	Admin-Vehículos
    5	Técnico
    6	Enfermero
    7	Médico
    8	Ayudante
    9	Prácticas
    */

    //get search term
    $searchTerm = $_GET['term'];

    //get matched data from skills table
    $query = $gestambu->query("SELECT CONCAT_WS(' ', usNom, usApe) AS medico, usCate, usEst
      FROM user
      WHERE CONCAT_WS(' ', usNom, usApe) LIKE '%$searchTerm%' AND usCate='7' AND usEst ='1'
      ORDER BY medico ASC
      ");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['medico'];
    }

    //return json data
    echo json_encode($data);
?>
