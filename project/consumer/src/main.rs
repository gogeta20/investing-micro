mod config;
use tokio;
use lapin::{options::*, types::FieldTable, Connection, ConnectionProperties};
use lapin::options::QueueDeclareOptions; // Importación adicional
use futures_util::StreamExt;
use uuid::Uuid;
// use log::{info, error};

#[tokio::main]
async fn main() {
    let amqp_addr = config::get_amqp_addr();
    println!("AMQP Address: {}", amqp_addr);

    match Connection::connect(&amqp_addr, ConnectionProperties::default()).await {
        Ok(conn) => {
            println!("Conexión a RabbitMQ establecida con éxito.");

            let channel = conn.create_channel().await.expect("Error al crear el canal");

            let queue_name = "messages";
            let consumer_tag = format!("rust_consumer_{}", Uuid::new_v4());

            let mut consumer = channel
                .basic_consume(
                    queue_name,
                    &consumer_tag,
                    BasicConsumeOptions::default(),
                    FieldTable::default(),
                )
                .await
                .expect("Error al iniciar el consumidor");

            println!("Esperando mensajes...");

            while let Some(delivery) = consumer.next().await {
                match delivery {
                    Ok(delivery) => {
                        let message = String::from_utf8_lossy(&delivery.data);
                        println!("Mensaje recibido: {}", message);

                        delivery
                            .ack(BasicAckOptions::default())
                            .await
                            .expect("Error al confirmar el mensaje");
                    }
                    Err(err) => {
                        println!("Error al recibir el mensaje: {:?}", err);
                    }
                }
            }
        }
        Err(e) => {
            println!("Error al conectar a RabbitMQ: {:?}", e);
        }
    }
}
