use crate::api::domain::interface::iresponse_repository::IResponseRepository;
use crate::api::infrastructure::services::mongo_response_repository::MongoResponseRepository;
use actix_web::{web, HttpResponse, Responder};
use std::error::Error;

pub async fn start(path: web::Path<String>) -> impl Responder {
    let intent = path.into_inner();
    println!("📌 Intent recibido: {}", intent); // 👈 Este es el log que quieres
    let repository = MongoResponseRepository::new().await;
    match repository.get_response_by_intent(&intent).await {
        Ok(response) => {
            HttpResponse::Ok().json(response)
        },
        Err(e) => {
            HttpResponse::InternalServerError().body(format!("Error: {}", e))
        }
    }
}