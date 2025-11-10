use serde::{Serialize, Deserialize};

#[derive(Debug, Serialize, Deserialize, Clone)]
pub struct ConversationState {
    pub uuid: String,
    pub intent: Option<String>,
    pub personas: Option<u32>,
    pub hora: Option<String>,
    pub mesa: Option<String>,
}

impl ConversationState {
    pub fn new(uuid: String) -> Self {
        ConversationState {
            uuid,
            intent: None,
            personas: None,
            hora: None,
            mesa: None,
        }
    }

    // Opcionalmente, método para saber si la conversación está completa
    pub fn is_complete(&self) -> bool {
        self.intent.is_some() && self.personas.is_some() && self.hora.is_some()
    }
}
