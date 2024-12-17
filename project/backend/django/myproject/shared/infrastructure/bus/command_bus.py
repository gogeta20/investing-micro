class CommandBus:
    def __init__(self):
        self._handlers = {}

    def register(self, command_class, handler):
        self._handlers[command_class] = handler

    def dispatch(self, command):
        handler = self._handlers.get(type(command))
        if not handler:
            raise Exception(f"No handler for {type(command)}")
        handler.handle(command)
