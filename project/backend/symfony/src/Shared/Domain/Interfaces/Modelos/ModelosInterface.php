<?php

namespace App\Shared\Domain\Interfaces\Modelos;

use Xorfor\Domain\LoginSession\LoginSession;

interface ModelosInterface
{
    public function permisosEdicionModelo(LoginSession $loginSession): bool;

    public function permisosModeloConsultable(LoginSession $loginSession): bool;

    public function modelosDistritos($modelos);

    public function outrosAproveitamentos($modelos);

    public function modelosEspecies($modelos, bool $isClon = false);

    public function concellosExcluidos($modelos);

    public function comprobarAccesoModelo($idModelo, $idUser, $esUsuarioExterno);

}
