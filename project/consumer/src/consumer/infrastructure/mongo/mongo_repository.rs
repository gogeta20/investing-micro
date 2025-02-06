use dotenv::dotenv;
use std::env;
use async_trait::async_trait;
use mongodb::{options::ClientOptions, Client, Database};
use crate::consumer::domain::interface::message_repository::MessageRepository;
use crate::consumer::domain::models::{Data, DataCollection};

pub struct MongoMessageRepository {
    db: Database,
}

impl MongoMessageRepository {
    pub async fn new() -> Self {
        dotenv().ok();

        // let connection_string = env::var("MONGO_DB_URL").expect("MONGO_DB_URL no está configurada");
        let connection_string = String::from("mongodb://root:password@34.45.214.187:27017");
        // let db_name = env::var("MONGO_DB_NAME").expect("MONGO_DB_NAME no está configurada");
        let db_name = String::from("pokemondb");

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
    async fn save(&self, data: Data) -> Result<(), String> {
        let collection: mongodb::Collection<DataCollection> = self.db.collection("pokemon_base_view");

        let data_collection: DataCollection = data.into();

        collection
            .insert_one(data_collection)
            .await
            .map_err(|e| format!("Error al guardar el mensaje: {}", e))?;
        Ok(())
    }
}
