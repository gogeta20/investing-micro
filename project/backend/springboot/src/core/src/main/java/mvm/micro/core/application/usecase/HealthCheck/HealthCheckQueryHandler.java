package mvm.micro.core.application.usecase.HealthCheck;

import mvm.micro.shared.domain.bus.query.QueryHandler;
import mvm.micro.shared.infrastructure.BaseResponse;
import org.springframework.stereotype.Service;
import java.util.HashMap;
import java.util.Map;

@Service("HealthCheckQueryHandler")
public class HealthCheckQueryHandler implements QueryHandler<HealthCheckQuery, BaseResponse> {
    @Override
    public BaseResponse handle(HealthCheckQuery query) {
        Map<String, Object> initialData = new HashMap<>();
        initialData.put("status", "ok");

        return new BaseResponse(new HashMap<>(initialData));
    }
}
