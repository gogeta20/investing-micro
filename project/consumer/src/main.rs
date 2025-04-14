mod config;
mod rabbit;
mod consumer;
mod api;
mod shared;

use std::error::Error;
use crate::consumer::application::use_case::listining_rabbit;
use crate::api::application::routing::routing::get_routes;
use actix_cors::Cors;
use actix_web::{App, HttpServer};

#[tokio::main]
async fn main() -> Result<(), Box<dyn Error>> {
    println!("🚀 Iniciando servicio...");

    let http_handle = tokio::spawn(async {
        println!("🌐 Iniciando servidor HTTP en 0.0.0.0:9080");

        HttpServer::new(|| {
            let cors = Cors::default()
                .allow_any_origin()
                .allow_any_method()
                .allow_any_header()
                .max_age(3600);

            App::new()
                .wrap(cors)
                .service(get_routes())
        })
            .bind(("0.0.0.0", 9080))
            .expect("❌ Error al iniciar servidor HTTP")
            .run()
            .await
            .expect("❌ Error en la ejecución del servidor HTTP");
    });

    let rabbit_handle = tokio::spawn(async {
        println!("📩 Iniciando escucha de RabbitMQ...");
        listining_rabbit::start()
            .await
            .expect("❌ Error al escuchar RabbitMQ");
    });

    // Espera a que ambas tareas finalicen
    tokio::try_join!(http_handle, rabbit_handle)?;

    Ok(())
}
