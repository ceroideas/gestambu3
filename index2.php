<html>
    <head>
        <title>Welcome to LAMP Infrastructure</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <?php
            try {
                $conn = mysqli_connect('db', 'root', 'd4t4-B4$3$', "gestambu3");

                $query = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
                $conn->query($query);
                $conn->close();
                echo "ONLY_FULL_GROUP_BY RULE EXECUTED!";
            } catch (\Throwable $th) {
                throw $th;
            }
            ?>
        </div>
    </body>
</html>
