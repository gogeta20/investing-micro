use bson::doc;
use actix_web::{web, HttpResponse, Responder};
use crate::shared::api::api_response::ApiResponse;
use crate::api::infrastructure::db::mongo_repository::MongoRepository;
use crate::api::domain::interface::imongo_repository::IMongoRepository;

pub async fn start(path: web::Path<String>) -> impl Responder {
    let intent = path.into_inner();
    println!("📌 Intent recibido: {}", intent);

    let repository = MongoRepository::new("intents_restaurant", "intents_db").await;
    let filter = doc! { "intent": &intent };

    match repository.find_one(filter).await {
        Ok(Some(document)) => {
            // Extraemos el campo "response" del documento BSON
            let response = document.get_str("response").unwrap_or("Sin respuesta disponible");
            let api_response = ApiResponse::<Vec<String>>::success(vec![response.to_string()]);
            HttpResponse::Ok().json(api_response)
        },
        Ok(None) => {
            let api_response = ApiResponse::<Vec<String>>::error("No se encontraron resultados");
            HttpResponse::Ok().json(api_response)
        },
        Err(e) => {
            let error = e.to_string();
            let response = ApiResponse::<Vec<String>>::error(&error);
            HttpResponse::InternalServerError().json(response)
        }
    }
}