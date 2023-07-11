<?php
function getPages($total, $n, $results_per_page = 10) {
    if ($n <= 0)
        return 1;

    $result = ceil($total / $n);
    return $result;
}

//obtenemos el offset y limit para la consulta sql.
function getOffsetAndLimit($page, $results_per_page = 10){
    $page = isset($page) ? $page : 1;
    $offset = ($page-1 <= 0 ? 0 : $page-1) * $results_per_page;
    return " ".$offset.",".$results_per_page;
}
?>


<?php function displayPaginateComponent($total_records, $total_pages, $page, $pageKey = "page")
{
    // solo mostramos el listado de paginas cuando las paginas totales sean mas de 1 (mayor a 0)
    // y cuando los registros totales son mayores a 10 puestoque el numero de registros por pagina es 10.
    if ($total_pages > 0) { ?>

        <ul class="pagination">
            <!-- si la pagina actual es mayor a 1 significa que no tiene pagina anterior
            por lo cual no es necesario renderizar este boton. -->
            <?php if ($page > 1) { ?>
                <li class="<?($page <= 1) ?: 'disabled'; ?>">
                    <a onclick='getRouteParams(
                    <?php echo $page + -1;?>,
                    <?php echo $total_pages;?>,
                    <?php echo json_encode($_GET);?>,
                    <?php echo $pageKey?>
                    );'>Anterior</a>
                </li>
            <?php } ?>
                    
            <?php
            for ($i = 0; $i <= $total_pages && $i <= 9; $i++) {

                if ($total_pages >= 1 && $i <= $total_pages) {
                    //debido a que los array vienen en la posicion 0
                    // el $page se muestra sumandole uno para que el listado
                    // de paginas se vea como 1,2,3,4 y no 0,1,2,3
                    // para proceso interno se maneja desde el 0.
                    // si la posicion del boton de pagina es igual a la pagina actual
                    // agregamos la clase activa
                    if ($i == $page - 1) {
                        ?>
                        <li onclick='getRouteParams(
                        <?php echo $i + 1;?>,
                        <?php echo $total_pages;?>,
                        <?php echo json_encode($_GET);?>,
                        <?php echo "\"${pageKey}\"";?>
                        );'
                        class="page-item active">
                        <a class="page-link">
                            <?php echo $i + 1?>
                        </a>
                    </li>
                   <?php } else { ?>
                        <li onclick='getRouteParams(
                        <?php echo $i + 1;?>,
                        <?php echo $total_pages;?>,
                        <?php echo json_encode($_GET);?>,
                        <?php echo "\"${pageKey}\"";?>
                        );'
                        class="page-item">
                        <a class="page-link">
                            <?php echo $i + 1?>
                        </a>
                    </li>
                    <?php }
                    if ($i > 9 || $total_pages == $i +1) break;
                }
            }
            
            ?>
            <li class="disabled page-item">
            <?php if ($total_pages >= 9) { ?>
                <a class="page-link" > <?php echo  $total_pages . '+...' ?></a>
            </li>

            <?php } if ($page < $total_pages) { ?>
                <li class="<?php echo ($page >= $total_pages) ?: 'disabled';?>">
                    <a onclick='getRouteParams(
                    <?php echo $page + 1;?>,
                    <?php echo $total_pages;?>,
                    <?php echo json_encode($_GET);?>
                    <?php echo $total_pages;?>,
                    );'>Siguiente</a>
                </li>
            <?php } ?>

        </ul>

    <?php } ?>

<?php } ?>



<script>
    var diaIni;
    var diaFin;

    document.addEventListener("DOMContentLoaded", function(event) {
        $("#searchBetweenForm").submit(function(event){
        event.preventDefault();
        const [inicio,fin] = $(this).serializeArray();
        const urlWithParams = new URL(window.origin);
        const params = <?php echo json_encode($_GET);?>;

        urlWithParams.searchParams.append(inicio.name,inicio.value);
        urlWithParams.searchParams.append(fin.name,fin.value);
        for (const key of Object.keys(params)) {
            if(urlWithParams.searchParams.has(key)) continue;
            urlWithParams.searchParams.append(key, params[key]);
        }
        
        window.location.replace(window.location.pathname + urlWithParams.search);
        });
    });

    function getRouteParams(page, total_pages, filters, pageKey) {
        const params = filters;

        if (page < 1 || page > total_pages) {
            return;
        }

        diaIni = $('input[name=diaIni]').val();
        diaFin = $('input[name=diaFin]').val();

        const urlWithParams = new URL(window.origin);

        urlWithParams.searchParams.append("diaIni",diaIni);
        urlWithParams.searchParams.append("diaFin",diaFin);
        urlWithParams.searchParams.append(pageKey,page);
        for (const key of Object.keys(params)) {
            if(urlWithParams.searchParams.has(key)) continue;
            urlWithParams.searchParams.append(key, params[key]);
        }
        
        window.location.replace(window.location.pathname + urlWithParams.search);
    }

</script>