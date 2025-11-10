use std::eprintln;
use async_trait::async_trait;

#[async_trait]
pub trait IResponseRepository {
    async fn find_response_by_intent(&self, intent: &str) -> Option<String>;
    async fn get_response_by_intent(&self, intent: &str) -> Result<Option<String>, Box<dyn std::error::Error + Send + Sync>>;

}
