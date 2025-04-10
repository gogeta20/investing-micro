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
use tokio::task::JoinHandle;

#[tokio::main]
async fn main()  -> Result<(), Box<dyn std::error::Error>> {
    // let rabbit_handle = tokio::spawn(async {
    //     listining_rabbit::start().await.expect("Error al escuchar RabbitMQ");
    // });

    HttpServer::new(|| {
        let cors = Cors::default()
            .allow_any_origin()
            .allow_any_method()
            .allow_any_header()
            .max_age(3600);

        App::new()
            .wrap(cors) // 👈 Importante, añade aquí el middleware
            .service(get_routes())
    })
        .bind(("0.0.0.0", 9080))?
        .run()
        .await?;
    //
    // HttpServer::new(|| {
    //     App::new().service(get_routes())
    // })
    //     .bind(("0.0.0.0", 9080))?
    //     .run()
    //     .await?;

    Ok(())
    //
    // let http_handle = tokio::spawn(async {
    //     actix_web::HttpServer::new(|| {
    //         App::new().service(get_routes())
    //     }).bind(("0.0.0.0", 9080))?.run().await
    // });
    // tokio::try_join!(http_handle)?;
    // Ok(())
}