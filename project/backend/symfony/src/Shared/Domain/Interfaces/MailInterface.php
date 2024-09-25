<?php

namespace App\Shared\Domain\Interfaces;

use Integraciones\Application\AvisosApi\EnvioMail\EnvioMailQuery;

interface MailInterface
{
    function createEnvioMailQuery($params): EnvioMailQuery;

    function createEnvioSimplificadoMailQuery(
        string $mensaje,
        string $asunto,
        string $to,
        ?string $minutos = "",
        ?string $entregaDia = "",
        ?string $entregaHora = "",
        ?string $entregaMinutos = ""
    ): EnvioMailQuery;

    function obtenerEmailSugerencia();
    function sugerenciaSetup($mensaje,$email);

}
