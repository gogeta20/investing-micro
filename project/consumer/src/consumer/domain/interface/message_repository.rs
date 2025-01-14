use async_trait::async_trait;
use crate::consumer::domain::models::Data;

#[async_trait]
pub trait MessageRepository {
    async fn save(&self, data: Data) -> Result<(), String>;
}
