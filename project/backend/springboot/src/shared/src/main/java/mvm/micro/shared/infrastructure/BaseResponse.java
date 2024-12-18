package mvm.micro.shared.infrastructure;

import mvm.micro.shared.domain.bus.query.Response;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;
import java.util.Map;

public class BaseResponse implements Response {
    private final Map<String, Object> data;
    private Map<String, Object> response;
    private String message;
    private int status;

    public BaseResponse(Map<String, Object> param) {
        this.data = param;
        this.response = new HashMap<>();
        this.message = "";
        this.status = 204;
    }

    public Map<String, Object> getData() {
        return data;
    }

    public Map<String, Object> getResponse() {
        return response;
    }

    public String getMessage() {
        return message;
    }

    public int getStatus() {
        return status;
    }

    public void setResponse(Map<String, Object> response) {
        this.response = response;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public Object emptyFormat(Object dato, boolean primary) {
        if (dato == null) {
            return primary ? 0 : "-";
        }

        if (dato instanceof LocalDateTime) {
            return dateFormat((LocalDateTime) dato);
        }

        if (dato instanceof Map || dato instanceof java.util.List) {
            return dato;
        }

        if (dato instanceof Boolean) {
            return dato;
        }

        if (primary) {
            return dato.toString().isEmpty() ? 0 : Integer.parseInt(dato.toString());
        }

        return dato.toString().isEmpty() ? "-" : dato.toString();
    }

    public String dateFormat(LocalDateTime dato) {
        if (dato == null) {
            return "-";
        }
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
        return dato.format(formatter);
    }
}
