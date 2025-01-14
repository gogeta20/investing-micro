use crate::consumer::domain::interface::message_repository::MessageRepository;
use crate::consumer::domain::models::{Data};
use crate::consumer::infrastructure::mongo::mongo_repository::MongoMessageRepository;

pub struct NewItem {
}

impl NewItem {
    pub async fn new() -> Self
    {
        Self {}
    }

    pub async fn add(&self, data: Data) {
        let repository = MongoMessageRepository::new().await;
        match repository.save(data).await {
            Ok(_) => {
                println!("Mensaje guardado con éxito");
            },
            Err(e) => {
                eprintln!("Error al procesar el evento: {:?}", e);
            },
        }
    }
}