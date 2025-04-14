use crate::api::domain::interface::imongo_repository::IMongoRepository;
use crate::api::domain::models::conversation::ConversationDocument;
use crate::api::infrastructure::db::mongo_repository::MongoRepository;

pub struct ConversationService;

impl ConversationService {
    pub async fn save_or_update(uuid: &str, intent: &str, estado: &str, respuesta_anterior: &str) {
        let repository = MongoRepository::new("intents_db", "conversations_restaurant").await;

        let conversation = ConversationDocument {
            uuid: uuid.to_string(),
            intent: intent.to_string(),
            estado_conversacion: estado.to_string(),
            respuesta_anterior: respuesta_anterior.to_string(),
            personas: None,
            hora: None,
        };

        if let Err(e) = repository.save(conversation).await {
            eprintln!("Error guardando la conversación: {:?}", e);
        } else {
            println!("📝 Conversación guardada/actualizada con éxito");
        }
    }
}
