<?php
session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <script>
        function cambiar(el, id) {
            el.preventDefault();
            const oculto = document.getElementById('oculto');
            oculto.setAttribute('value', id);
        }
    </script>
</head>

<body>
    <?php
    require '../../vendor/autoload.php';

    if ($usuario = \App\Tablas\Usuario::logueado()) {
        if (!$usuario->es_admin()) {
            $_SESSION['error'] = 'Acceso no autorizado.';
            return volver();
        }
    } else {
        return redirigir_login();
    }

    $categoria = obtener_post('cupon');
    $guardar = obtener_post("guardar");

    if(isset($guardar)) {

    
        $pdo = conectar();
        
    
        $borrar = $pdo->prepare("DELETE FROM cupones WHERE cupon = :cupon");
        $borrar->execute([':cupon' => $cupon]);

    
        $_SESSION['exito'] = 'Has borrado el cupon';
        volver_admin();

        }

    ?>

<form method="POST" action="">
    <h2> Introduce el cupon que quieras borrar : </h2>
            <label>
            <select id="cupon" name="cupon">
                <?php
                $pdo = conectar(); 
                $cupones = $pdo->query("SELECT * FROM cupones"); ?>
                <?php foreach($cupones as $cupo): ?>  
                    <option name="cupon" value="<?= $cupo['cupon'] ?>"> <?= hh($cupo['cupon']) ?>  </option>
                <?php endforeach ?>
            </select>
            <label>
                <input type="submit" value="guardar" name="guardar" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></input>
            </label>
            </label>