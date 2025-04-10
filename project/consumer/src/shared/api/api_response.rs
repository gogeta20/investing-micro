use serde::Serialize;

#[derive(Debug, Serialize)]
pub struct ApiResponse<T> {
    pub data: Option<T>,
    pub status: u16,
    pub message: String,
}

impl<T: Serialize> ApiResponse<T> {
    pub fn success(data: T) -> Self {
        ApiResponse {
            data: Some(data),
            status: 200,
            message: "Success".to_string(),
        }
    }

    pub fn error(message: &str) -> Self {
        ApiResponse {
            data: None,
            status: 500,
            message: message.to_string(),
        }
    }
}
