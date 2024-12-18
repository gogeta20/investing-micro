package mvm.micro.shared.domain.bus.command;

public interface CommandBus {
    void dispatch(Command command);
}
