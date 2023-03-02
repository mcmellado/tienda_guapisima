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

    $cupon = obtener_post('cupon');
    $fecha = obtener_post('fecha');
    $descuento = obtener_post('descuento');
    $guardar = obtener_post("guardar");
    $errores = ['cupon' => [], 'fecha' => [], 'descuento' => []];

    if(isset($guardar)) {

        $hoy = date('Y-m-d');
        $hoy_unix = strtotime($hoy);
    
        $pdo = conectar();
        $sent = $pdo->query("SELECT * from cupones");
        
        foreach($sent as $array) {
           if($array['cupon'] == $cupon) {
            $errores['cupon'][] = 'Ya existe ese cupon.';
           } 
        }

        if (!preg_match('/^[0-9]*$/', $descuento)) {
            $errores['descuento'][] = 'Sólo puede ingresar datos de tipo numérico';
        }

        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha)) {
            $errores['fecha'][] = 'Sólo puedes ingresar fechas tipo dd/mm/yyyy';
        
        } else {

            if(strtotime($fecha) <= $hoy_unix) {
                $errores['fecha'][] = "El cupon ha caducado";
            }

        }


        $vacio = true;

        foreach ($errores as $err) {
            if (!empty($err)) {
                $vacio = false;
                break;
            }
        }
        
    
        if($vacio) {
            $pdo = conectar();
    
            $insertar = $pdo->prepare("INSERT INTO cupones (cupon, descuento, fecha) VALUES (:cupon, :descuento, :fecha)");
            $insertar->execute([':cupon' => $cupon,
                                ':descuento' => $descuento,
                                ':fecha' => $fecha]);

            $_SESSION['exito'] = 'Has introducido un nuevo cupon';
            volver_admin();

        }
    }

    ?>

<form method="POST" action="">
    <h2> Introduce el nombre el nuevo cupon: </h2>
            <label>
                <input type="text" name="cupon" value="<?= $cupon ?>">
            <?php foreach ($errores['cupon'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
            <?php endforeach ?>
            </label>
    <h2> Introduce el descuento de el cupon: </h2>
            <label>
                <input type="text" name="descuento" value="<?= $descuento ?>">
            <?php foreach ($errores['descuento'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
            <?php endforeach ?>
            </label>
    <h2> Introduce la fecha de caducidad del cupon: </h2>
            <label>
                <input type="text" name="fecha" value="<?= $fecha ?>">
            <?php foreach ($errores['fecha'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
            <?php endforeach ?>
            <br>
            <label>
                <input type="submit" value="guardar" name="guardar" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></input>
            </label>
            </label>