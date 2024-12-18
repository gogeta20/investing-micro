package mvm.micro.shared.infrastructure.bus.command;

import mvm.micro.shared.domain.bus.command.Command;
import mvm.micro.shared.domain.bus.command.CommandBus;
import mvm.micro.shared.domain.bus.command.CommandHandler;
import org.springframework.context.ApplicationContext;
import org.springframework.stereotype.Service;

@Service
public class InMemoryCommandBus implements CommandBus {
    private final ApplicationContext context;

    public InMemoryCommandBus(ApplicationContext context) {
        this.context = context;
    }

    @Override
    public void dispatch(Command command) {
        // Buscar el handler correcto para este comando
        String handlerBeanName = command.getClass().getSimpleName() + "Handler";
        CommandHandler<Command> handler =
            (CommandHandler<Command>) context.getBean(handlerBeanName);

        if (handler != null) {
            handler.handle(command);
        } else {
            throw new RuntimeException("No handler found for command: " + command.getClass().getSimpleName());
        }
    }
}
