package mvm.micro.shared.infrastructure.controller;

import mvm.micro.shared.domain.bus.command.Command;
import mvm.micro.shared.domain.bus.command.CommandBus;
import mvm.micro.shared.domain.bus.query.Query;
import mvm.micro.shared.domain.bus.query.QueryBus;
import mvm.micro.shared.domain.bus.query.Response;

public abstract class ApiController {
    private final CommandBus commandBus;
    private final QueryBus queryBus;

    protected ApiController(CommandBus commandBus, QueryBus queryBus) {
        this.commandBus = commandBus;
        this.queryBus = queryBus;
    }

    protected void dispatch(Command command) {
        commandBus.dispatch(command);
    }

    protected <R extends Response> R ask(Query query) {
        return queryBus.ask(query);
    }

    // Método para manejar excepciones (puedes expandirlo)
    protected abstract void registerExceptions();
}
