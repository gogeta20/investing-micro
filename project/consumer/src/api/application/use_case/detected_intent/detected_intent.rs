use actix_web::{web, HttpResponse, Responder};
use serde::Deserialize;
use bson::{doc, from_document};
use crate::api::infrastructure::db::mongo_repository::MongoRepository;
use crate::api::domain::interface::imongo_repository::IMongoRepository;
use crate::api::domain::models::conversation::ConversationDocument;
use crate::shared::api::api_response::ApiResponse;

#[derive(Debug, Deserialize)]
pub struct ConversationInput {
    pub uuid: String,
    pub intent: String,
    pub respuesta_usuario: String,
}

pub async fn start(payload: web::Json<ConversationInput>) -> impl Responder {
    let input = payload.into_inner();
    println!("📌 Intent recibido: {}", input.intent);
    println!("📌 UUID Conversación: {}", input.uuid);
    println!("📌 Respuesta del usuario: {}", input.respuesta_usuario);

    let conversation_repo = MongoRepository::new("conversations_restaurant", "intents_db").await;

    let filter_conversation = doc! { "uuid": &input.uuid };
    let existing_conversation = conversation_repo.find_one(filter_conversation.clone()).await;

    match existing_conversation {
        Ok(Some(_document)) => {
            println!("📝 Conversación existente encontrada, actualizando...");

            let conversation: ConversationDocument = from_document(_document).unwrap();

            println!("🚀 Estado actual de la conversación: {:?}", conversation);

            let mut next_question = "Perfecto, reserva completa. ¡Te esperamos! 🎉";

            if "numero_comensales" == input.intent && conversation.personas.is_none() {
                if let Err(e) = conversation_repo.update_field(&input.uuid, "personas", &input.respuesta_usuario).await {
                    eprintln!("❌ Error actualizando personas: {:?}", e);
                }
                next_question = "¿A qué hora deseas la reserva?";
            } else if "hora_reserva" == input.intent && conversation.hora.is_none() {
                if let Err(e) = conversation_repo.update_field(&input.uuid, "hora", &input.respuesta_usuario).await {
                    eprintln!("❌ Error actualizando hora: {:?}", e);
                }
                next_question = "Perfecto, reserva completa. ¡Te esperamos! 🎉";
            }

            let response = ApiResponse::<Vec<String>>::success(vec![next_question.to_string()]);
            return HttpResponse::Ok().json(response);


            let response = ApiResponse::<Vec<String>>::success(vec!["Conversación actualizada con éxito".to_string()]);
            return HttpResponse::Ok().json(response);
        },
        Ok(None) => {
            println!("🆕 No existe conversación, creando una nueva...");

            // 👉 Buscamos la respuesta inicial de la intención detectada
            let intent_repo = MongoRepository::new("intents_restaurant", "intents_db").await;
            let filter_intent = doc! { "intent": &input.intent };
            let intent_result = intent_repo.find_one(filter_intent).await;

            match intent_result {
                Ok(Some(document)) => {
                    let respuesta = document.get_str("response").unwrap_or("Sin respuesta disponible");

                    // ✅ Guardamos la nueva conversación en la colección
                    let conversation = doc! {
                        "uuid": &input.uuid,
                        "intent": &input.intent,
                        "respuesta_anterior": &input.respuesta_usuario,
                        "estado_conversacion": "en_proceso"
                    };

                    if let Err(e) = conversation_repo.insert_one(conversation).await {
                        eprintln!("Error guardando la conversación: {:?}", e);
                        let response = ApiResponse::<Vec<String>>::error("Error al guardar la conversación");
                        return HttpResponse::InternalServerError().json(response);
                    }

                    // ✅ Enviamos la primera respuesta al frontend
                    let response = ApiResponse::<Vec<String>>::success(vec![respuesta.to_string()]);
                    HttpResponse::Ok().json(response)
                },
                Ok(None) => {
                    let response = ApiResponse::<Vec<String>>::error("No se encontró la intención en la base de datos");
                    HttpResponse::Ok().json(response)
                },
                Err(e) => {
                    let response = ApiResponse::<Vec<String>>::error(&e.to_string());
                    HttpResponse::InternalServerError().json(response)
                }
            }
        },
        Err(e) => {
            eprintln!("Error consultando la conversación: {:?}", e);
            let response = ApiResponse::<Vec<String>>::error(&e.to_string());
            HttpResponse::InternalServerError().json(response)
        }
    }
}



//
// let repository = MongoRepository::new("intents_restaurant", "intents_db").await;
// let filter = doc! { "intent": &input.intent };
//
// match repository.find_one(filter).await {
// Ok(Some(document)) => {
// let response = document.get_str("response").unwrap_or("Sin respuesta disponible");
//
// // Guardamos el contexto de la conversación en MongoDB 📝
// ConversationService::save_or_update(
// &uuid,
// &intent,
// "esperando_numero_personas", // Estado actual de la conversación
// "" // Respuesta anterior vacía por ahora
// ).await;
//
// let api_response = ApiResponse::<Vec<String>>::success(vec![response.to_string()]);
// HttpResponse::Ok().json(api_response)
// },
// Ok(None) => {
// let api_response = ApiResponse::<Vec<String>>::error("No se encontraron resultados");
// HttpResponse::Ok().json(api_response)
// },
// Err(e) => {
// let error = e.to_string();
// let response = ApiResponse::<Vec<String>>::error(&error);
// HttpResponse::InternalServerError().json(response)
// }
// }