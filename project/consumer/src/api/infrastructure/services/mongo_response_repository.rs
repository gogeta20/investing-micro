use std::env;
use crate::api::domain::interface::iresponse_repository::IResponseRepository;
use async_trait::async_trait;
use bson::doc;
use mongodb::{options::ClientOptions, Client, Collection};
use serde::{Deserialize, Serialize};
use dotenv::dotenv;

#[derive(Debug, Serialize, Deserialize)]
pub struct ResponseDocument {
    pub intent: String,
    pub response: String,
}

pub struct MongoResponseRepository {
    collection: Collection<ResponseDocument>,
}

impl MongoResponseRepository {
    pub async fn new() -> Self {
        dotenv().ok();
        let connection_string = env::var("MONGO_DB_URL").expect("MONGO_DB_URL no está configurada");
        println!("📌 Conexión a Mongo: {}", connection_string);
        // Aquí sería bueno más adelante mover a dotenv como comentamos 😉
        let connection_string = "mongodb://root:password@mongo_db:27017";
        let client_options = ClientOptions::parse(connection_string).await.expect("Error conectando a MongoDB");
        let client = Client::with_options(client_options).expect("Error creando cliente MongoDB");
        let db = client.database("intents_db");
        let collection = db.collection::<ResponseDocument>("intents_restaurant");
        Self { collection }
    }
}

#[async_trait]
impl IResponseRepository for MongoResponseRepository {
    async fn find_response_by_intent(&self, intent: &str) -> Option<String> {
        todo!()
    }

    async fn get_response_by_intent(&self, intent: &str) -> Result<Option<String>, Box<dyn std::error::Error + Send + Sync>> {
            let filter = doc! { "intent": intent };

            match self.collection.find_one(filter).await {
                Ok(Some(doc)) => Ok(Some(doc.response)),
                Ok(None) => Ok(None),
                Err(err) => {
                    eprintln!("Error consultando MongoDB: {:?}", err);
                    Err(Box::new(err))
                }
            }
        }
}
