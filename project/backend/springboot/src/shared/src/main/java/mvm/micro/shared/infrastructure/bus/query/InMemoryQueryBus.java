package mvm.micro.shared.infrastructure.bus.query;

import mvm.micro.shared.domain.bus.query.Query;
import mvm.micro.shared.domain.bus.query.QueryBus;
import mvm.micro.shared.domain.bus.query.QueryHandler;
import mvm.micro.shared.domain.bus.query.Response;
import org.springframework.context.ApplicationContext;
import org.springframework.stereotype.Service;

@Service
public class InMemoryQueryBus implements QueryBus {
    private final ApplicationContext context;

    public InMemoryQueryBus(ApplicationContext context) {
        this.context = context;
    }

    @Override
    @SuppressWarnings("unchecked")
    public <R extends Response> R ask(Query query) {
        String handlerBeanName = query.getClass().getSimpleName() + "Handler";
        System.out.println("Looking for handler: " + handlerBeanName);  // Debug
        try {
            QueryHandler<Query, R> handler =
                (QueryHandler<Query, R>) context.getBean(handlerBeanName);
            return handler.handle(query);
        } catch (Exception e) {
            System.out.println("Error finding handler: " + e.getMessage());  // Debug
            throw new RuntimeException("No handler found for query: " + query.getClass().getSimpleName());
        }
    }
}
