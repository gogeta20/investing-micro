from myproject.shared.domain.bus.command.command import Command


class CommandBus:
    def __init__(self):
        self._handlers = {}

    def register(self, command_class, handler):
        self._handlers[command_class] = handler

    def dispatch(self, command: Command):
        handler = self._handlers.get(type(command))
        if not handler:
            raise Exception(f"dispatch No handler for {type(command)}")
        handler.handle(command)
