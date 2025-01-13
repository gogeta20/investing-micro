use dotenv::dotenv;
use std::env;
use async_trait::async_trait;
use mongodb::{bson::{doc, Document}, options::ClientOptions, Client, Database};
use crate::consumer::domain::interface::message_repository::MessageRepository;

pub struct MongoMessageRepository {
    db: Database,
}

impl MongoMessageRepository {
    pub async fn new() -> Self {
        dotenv().ok();

        let connection_string = env::var("MONGO_DB_URL").expect("MONGO_DB_URL no está configurada");
        let db_name = env::var("MONGO_DB_NAME").expect("MONGO_DB_NAME no está configurada");

        let client_options = ClientOptions::parse(&connection_string)
            .await
            .expect("Error al conectar a MongoDB");
        let client = Client::with_options(client_options).expect("Error al crear cliente de MongoDB");
        let db = client.database(&db_name);
        Self { db }
    }
}

#[async_trait]
impl MessageRepository for MongoMessageRepository {
    async fn save(&self) -> Result<(), String> {
        let collection: mongodb::Collection<Document> = self.db.collection("pokemon_base_view");

        let new_doc = doc! {
            "example_key": "example_value"
        };

        // Insertar el documento en la colección
        collection
            .insert_one(new_doc)
            .await
            .map_err(|e| format!("Error al guardar el mensaje: {}", e))?;
        Ok(())
    }
}
