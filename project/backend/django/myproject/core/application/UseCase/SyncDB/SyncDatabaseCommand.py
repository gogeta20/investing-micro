from dataclasses import dataclass
from myproject.shared.domain.bus.command.command import Command

@dataclass
class SyncDatabaseCommand(Command):
    pass
