<?php
/**
 * Created by PhpStorm.
 * User: Ibai
 * Date: 29/2/16
 * Time: 11:39
 */

namespace Modelo\Base;

use Modelo\BD;

require_once __DIR__."/../BD/CalendarioBD.php";
//require_once __DIR__."/../BD/CalendarioBD-copia.php";
class Calendario{

    private $id;
    private $desc;
    private $estado;

    /**
     * Calendario constructor.
     * @param $id
     * @param $desc
     * @param $estado
     */
    public function __construct($id = null, $desc = null, $estado = null)
    {
        $this->setId($id);
        $this->setDesc($desc);
        $this->setEstado($estado);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }



}