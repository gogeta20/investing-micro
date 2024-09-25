<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use Integraciones\Shared\Domain\FileRepository;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Xorfor\Application\Permisos\CheckIfInstrumentoEditable\CheckIfInstrumentoEditable;
use Xorfor\Application\Permisos\CheckIfInstrumentoEditable\CheckIfInstrumentoEditableQuery;
use Xorfor\Domain\Exceptions\XorforUnauthorizedException;
use Xorfor\Domain\LoginSession\LoginSessionId;

final class ApiStateInstrumentListener
{
    public function __construct(
        private readonly TranslatorCustom    $translatorCustom,
        private readonly FileRepository      $fileLoginRepository,
        protected CheckIfInstrumentoEditable $checkIfInstrumentoEditable,
    )
    {
    }

    public function ControllerArgumentsEvent(ControllerArgumentsEvent $event)
    {
        $request = $event->getRequest();
        $bag = $request->attributes;
        $idxf = $bag->get('idxf') ? (int)$bag->get('idxf') : false;
        $uri = $request->getUri();

        $comprobarRuta = $this->comprobarRuta(uri: $uri, idxf: $idxf);
        if ($idxf && $comprobarRuta) {
            $method = $request->getMethod();
            $headers = $request->headers;
            $sesionUser = $headers->get('Authorization');
            $session = $this->fileLoginRepository->search(new LoginSessionId($sesionUser));
            $usuario = $session->loginSessionUser()->loginSessionUserIdentification()->value();
            $tipoLogin = $session->loginSessionTipoLogin()->value();
            $currentRouteId = $bag->get('_route');
            $routesPuedenEditarSoloRevision = [
                'update_descricion_actuaciones_instrumento',
                'update_grado_seguimiento_instrumento',
            ];
            $routesPuedenEditarUsuariosInternos = [
                'post_informe_global',
            ];
            try {
                $instrumentoEditable = $this->checkIfInstrumentoEditable->__invoke(
                    query: new CheckIfInstrumentoEditableQuery(
                        idxf: $idxf,
                        usuario: $usuario
                    ),
                    session: $session
                );

                if (($method == "GET") && (!$instrumentoEditable['puedeConsultar'])) {
                    throw new XorforUnauthorizedException(
                        $this->translatorCustom->translate('error.faltaPermisos', [], 'permisos')
                    );
                }

                if (!(($tipoLogin == 'casIntranet' || $tipoLogin == 'ldap') && in_array($currentRouteId, $routesPuedenEditarUsuariosInternos))) {
                    if (($method != "GET") && (
                            (!$instrumentoEditable['puedeEditar'] && !$instrumentoEditable['puedeEditarSoloRevision'])
                            || ($instrumentoEditable['puedeEditarSoloRevision'] && !in_array($currentRouteId, $routesPuedenEditarSoloRevision))
                        )) {
                        throw new XorforUnauthorizedException(
                            $this->translatorCustom->translate('error.faltaPermisos', [], 'permisos')
                        );
                    }
                }

                return $instrumentoEditable;
            } catch (XorforUnauthorizedException $e) {
                throw new XorforUnauthorizedException($e->getMessage(), 403);
            }
        }
    }

    public function comprobarRuta(string $uri, int|false $idxf): bool
    {
        if (!$idxf) {
            $comprobarRuta = false;
        } else {
            $dividirUri = explode("api/", $uri);
            $cadena = $dividirUri[1] ?? '';
            $buscarEnCadena = 'instrumentos/' . (string)$idxf;
            $comprobarRuta = (strpos($cadena, $buscarEnCadena) === false) ? false : true;
        }
        return $comprobarRuta;
    }
}
