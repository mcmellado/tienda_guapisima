<?php
namespace App\Tablas;

use PDO;

class Cupon extends Modelo
{
    protected static string $tabla = 'cupones';

    public $id;
    public $cupon;
    public $descuento;
    public $fecha;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->cupon = $campos['cupon'];
        $this->descuento = $campos['descuento'];
        $this->fecha = $campos['fecha'];

    }

    public function getId()
    {
        return $this->id;
    }

    public function getCupon()
    {
        return $this->cupon;
    }

    public function getDescuento()
    {
        return $this->descuento;
    }

    public function getFecha()
    {
        return $this->fecha;
    }
}
