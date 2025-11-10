use crate::api::domain::interface::imongo_repository::IMongoRepository;
use async_trait::async_trait;
use bson::{doc, to_document, Document};
use mongodb::{options::ClientOptions, Client, Collection};
use std::error::Error;
use dotenv::dotenv;
use std::env;
use mongodb::options::UpdateOptions;
use serde::Serialize;

pub struct MongoRepository {
    collection: Collection<Document>,
}

impl MongoRepository {
    pub async fn new(collection_name: &str, database_name: &str) -> Self {
        dotenv().ok();
        let connection_string = env::var("MONGO_DB_URL").expect("MONGO_DB_URL no está configurada");
        // let connection_string = "mongodb://root:password@mongo_db:27017";
        let client_options = ClientOptions::parse(connection_string).await.expect("Error conectando a MongoDB");
        let client = Client::with_options(client_options).expect("Error creando cliente MongoDB");
        let db = client.database(database_name);
        let collection = db.collection::<Document>(collection_name);
        Self { collection }
    }
}

#[async_trait]
impl IMongoRepository for MongoRepository {
    async fn find_one(&self, filter: Document) -> Result<Option<Document>, Box<dyn Error + Send + Sync>> {
        let result = self.collection.find_one(filter).await?;
        Ok(result)
    }

    async fn insert_one(&self, document: Document) -> Result<(), Box<dyn Error + Send + Sync>> {
        self.collection.insert_one(document).await?;
        Ok(())
    }

    async fn save<T>(&self, item: T) -> Result<(), Box<dyn Error + Send + Sync>>
    where
        T: Serialize + Send + Sync,
    {
        // Convertimos la struct en Document BSON
        let document = to_document(&item)?;

        // Extraemos el uuid del documento para usarlo como filtro
        let uuid = document
            .get_str("uuid")
            .map_err(|_| "El documento no contiene el campo 'uuid'")?;

        let filter = doc! { "uuid": uuid };

        let update = doc! { "$set": document };

        let options = UpdateOptions::builder().upsert(true).build();

        self.collection.update_one(filter, update).await?;

        Ok(())
    }
    async fn update_field(
        &self,
        uuid: &str,
        field: &str,
        value: &str
    ) -> Result<(), Box<dyn std::error::Error + Send + Sync>> {
        let filter = doc! { "uuid": uuid };
        let update = doc! { "$set": { field: value } };

        self.collection.update_one(filter, update).await?;
        Ok(())
    }
}
