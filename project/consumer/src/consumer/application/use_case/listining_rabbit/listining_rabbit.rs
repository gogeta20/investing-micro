use std::error::Error;
use crate::consumer::domain::models::RabbitMessage;
use crate::consumer::infrastructure::rabbit::RabbitConsumer;

pub async fn start() -> Result<(), Box<dyn Error>> {
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

async fn launch_case_use(event: Result<RabbitMessage, Box<dyn Error>>) {
    match event {
        Ok(rabbit_message) => {
            println!("Evento recibido correctamente: {:?}", rabbit_message);
            println!("Event ID: {}", rabbit_message.eventId);
            println!("Nombre: {}", rabbit_message.data.nombre);
        }
        Err(err) => {
            eprintln!("Error al procesar el evento: {:?}", err);
        }
    }
}
