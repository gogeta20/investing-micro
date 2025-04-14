use std::error::Error;
use crate::consumer::application::use_case::sync_db::new_item::NewItem;
use crate::consumer::domain::models::{RabbitMessage};
use crate::consumer::infrastructure::rabbit::RabbitConsumer;

pub async fn start() -> Result<(), Box<dyn Error + Send + Sync>> {
    match RabbitConsumer::new("messages").await {
        Ok(rabbit) => {
            match rabbit.start_consuming().await {
                Ok(event) => {
                    launch_case_use(Ok(event)).await;
                }
                Err(e) => {
                    println!("Error durante el consumo: {:?}", e);
                    launch_case_use(Err(e)).await;
                }
            }
        }
        Err(e) => {
            println!("Error al conectar a RabbitMQ: {:?}", e);
        }
    }
    Ok(())
}

async fn launch_case_use(event: Result<RabbitMessage, Box<dyn Error + Send + Sync>>) {
    match event {
        Ok(rabbit_message) => {
            if rabbit_message.eventId == "PokemonCreate" {
                let data = rabbit_message.data;
                let new_item = NewItem::new().await;
                new_item.add(data).await;
            } else {
                println!("Evento recibido pero no es 'newItem': {:?}", rabbit_message.eventId);
            }
        }
        Err(err) => {
            eprintln!("Error al procesar el evento: {:?}", err);
        }
    }
}
