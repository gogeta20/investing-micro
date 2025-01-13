use async_trait::async_trait;

#[async_trait]
pub trait MessageRepository {
    async fn save(&self) -> Result<(), String>;
}
