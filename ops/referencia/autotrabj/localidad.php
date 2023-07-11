<?php
    include '../../../functions/function.php';

    //get search term
    $searchTerm = $_GET['term'];

    //get matched data from skills table
    $query = $gestambu->query("SELECT municipio
      FROM municipios
      WHERE municipio LIKE '%$searchTerm%'
      ORDER BY municipio ASC
      ");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['municipio'];
    }

    //return json data
    echo json_encode($data);
?>
