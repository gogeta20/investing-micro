class BaseResponse:
    def __init__(self, data: dict = None, message: str = "", status: int = 200):
        self.data = data or {}
        self.message = message
        self.status = status

    def to_dict(self):
        return {
            "data": self.data,
            "message": self.message,
            "status": self.status
        }
