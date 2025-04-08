// src/application/routing.rs
use actix_web::{web, Scope};
// use crate::consumer::application::use_case::serve_intent::handle_intent;
use crate::api::application::use_case::detected_intent::detected_intent;

pub fn get_routes() -> Scope {
    web::scope("/api")
        .route("/intent/{intent}", web::get().to(detected_intent::start))
}
