use async_trait::async_trait;
use std::error::Error;
use bson::Document;
use serde::Serialize;

#[async_trait]
pub trait IMongoRepository {
    async fn find_one(&self, filter: Document) -> Result<Option<Document>, Box<dyn Error + Send + Sync>>;
    async fn insert_one(&self, document: Document) -> Result<(), Box<dyn Error + Send + Sync>>;
    // Opcional: podríamos añadir update_one, delete_one, etc.
    async fn save<T>(&self, item: T) -> Result<(), Box<dyn Error + Send + Sync>>
    where
        T: Serialize + Send + Sync;
}
