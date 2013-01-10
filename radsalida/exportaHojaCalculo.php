<?php
header("Content-type: application/x-msdownload; charset=utf-8");
header("Content-Disposition: attachment; filename=planilla.xls");
session_start();
include_once "../include/db/ConnectionHandler.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <?php
        $sql = $_SESSION["SQLTMPLISTADO"];
        $db = new ConnectionHandler("..");
        $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $db->query($sql);
        ?>
        <table>
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>DIRECCIÓN</th>
                    <th>CIUDAD</th>
                    <th>DEPARTAMENTO</th>
                    <th>PESO</th>
                    <th>RADICADO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rs && !$rs->EOF) {
                    echo "<tr>";
                    echo "<td>" . $rs->fields['SGD_RENV_NOMBRE'] . "</td>";
                    echo "<td>" . $rs->fields['SGD_RENV_DIR'] . "</td>";
                    echo "<td>" . $rs->fields['SGD_RENV_DESTINO'] . "</td>";
                    echo "<td>" . $rs->fields['SGD_RENV_DEPTO'] . "</td>";
                    echo "<td>" . $rs->fields['SGD_RENV_PESO'] . "</td>";
                    echo "<td>" . $rs->fields['RADI_NUME_SAL'] . "</td>";
                    echo "</tr>";
                    $rs->MoveNext();
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
