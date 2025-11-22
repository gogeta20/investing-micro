import inspect
from typing import Any, get_type_hints, get_origin, get_args
from myproject.shared.domain.bus.command.command import Command
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.application.handlers.handler_loader import load_handlers


class CommandBus:
    def __init__(self, auto_register: bool = True):
        self._handlers = {}
        if auto_register:
            self._auto_register_handlers()

    def _auto_register_handlers(self):
        """Auto-registra todos los command handlers descubriendo qué Command manejan"""
        handlers_dict = load_handlers()
        for handler_name, handler_instance in handlers_dict.items():
            # Obtener la clase del handler para inspeccionar el método handle()
            handler_class = handler_instance.__class__

            # Solo procesar CommandHandlers
            if not issubclass(handler_class, CommandHandler):
                continue

            # Inspeccionar el método handle() para obtener el tipo del Command
            try:
                handle_method = getattr(handler_class, 'handle', None)
                if handle_method:
                    # Obtener las anotaciones de tipo del método handle
                    sig = inspect.signature(handle_method)
                    params = list(sig.parameters.values())
                    if len(params) > 1:  # self + command
                        command_param = params[1]
                        command_type = command_param.annotation

                        # Manejar tipos genéricos y opcionales
                        if command_type != inspect.Parameter.empty:
                            # Si es un tipo genérico, extraer el tipo base
                            origin = get_origin(command_type)
                            if origin:
                                command_type = get_args(command_type)[0] if get_args(command_type) else command_type

                            # Verificar que sea una clase y subclase de Command
                            if command_type and isinstance(command_type, type):
                                try:
                                    if issubclass(command_type, Command):
                                        self._handlers[command_type] = handler_instance
                                        print(f"[DEBUG] Auto-registrado Command: {command_type.__name__} -> {handler_name}")
                                        continue
                                except (TypeError, AttributeError):
                                    pass

                    # Si llegamos aquí, no se pudo detectar el tipo Command
                    print(f"[WARNING] No se encontró tipo Command válido para {handler_name}")

            except Exception as e:
                print(f"[WARNING] No se pudo auto-registrar {handler_name}: {e}")

    def register(self, command_class, handler):
        """Registro manual (opcional, para casos especiales)"""
        self._handlers[command_class] = handler

    def dispatch(self, command: Command):
        handler = self._handlers.get(type(command))
        if not handler:
            raise Exception(f"dispatch No handler for {type(command)}")
        return handler.handle(command)


# Instancia compartida del CommandBus (singleton pattern)
_shared_command_bus = CommandBus()


def get_command_bus() -> CommandBus:
    """Retorna la instancia compartida del CommandBus"""
    return _shared_command_bus
