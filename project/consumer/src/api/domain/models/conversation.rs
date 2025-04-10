use serde::{Deserialize, Serialize};

#[derive(Debug, Serialize, Deserialize)]
pub struct ConversationDocument {
    pub uuid: String,
    pub intent: String,
    pub estado_conversacion: String,
    pub respuesta_anterior: String,
}
