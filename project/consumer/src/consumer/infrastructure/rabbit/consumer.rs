use lapin::{
    options::*,
    types::FieldTable,
    Connection,
    ConnectionProperties,
    Channel,
};
use futures_util::StreamExt;
use uuid::Uuid;
use crate::config;
use serde_json::from_str;
use crate::consumer::domain::models::{Data, RabbitMessage};

pub struct RabbitConsumer {
    channel: Channel,
    queue_name: String,
}

// #[derive(Deserialize, Debug)]
// pub struct RabbitMessage {
//     pub data: String,
//     pub eventId: String,
// }

impl RabbitConsumer {
    pub async fn new(queue_name: &str) -> Result<Self, lapin::Error>
    {
        let amqp_addr = config::get_amqp_addr();
        let conn = Connection::connect(&amqp_addr, ConnectionProperties::default()).await?;
        let channel = conn.create_channel().await?;

        Ok(Self {
            channel,
            queue_name: queue_name.to_string(),
        })
    }

    pub async fn start_consuming(&self) -> Result<RabbitMessage, Box<dyn std::error::Error>> {
        let consumer_tag = format!("rust_consumer_{}", Uuid::new_v4());

        let mut consumer = self.channel
            .basic_consume(
                &self.queue_name,
                &consumer_tag,
                BasicConsumeOptions::default(),
                FieldTable::default(),
            )
            .await?;

        println!("Comenzando a consumir mensajes...  en start");

        while let Some(delivery_result) = consumer.next().await {
            match delivery_result {
                Ok(delivery) => {
                    let message = self.handle_message(&delivery).await?;
                    delivery
                        .ack(BasicAckOptions::default())
                        .await
                        .map_err(|e| format!("Error al hacer ACK: {:?}", e))?;
                    return Ok(message);
                }
                Err(err) => {
                    eprintln!("Error al recibir el mensaje: {:?}", err);
                }
            }
        }

        Err("No hay más mensajes o la conexión se cerró".into())
    }

    async fn handle_message(
        &self,
        delivery: &lapin::message::Delivery,
    ) -> Result<RabbitMessage, Box<dyn std::error::Error>> {
        let message = String::from_utf8_lossy(&delivery.data);
        let parsed_message: RabbitMessage = from_str(&message)
            .map_err(|e| format!("Error al deserializar el mensaje: {:?}", e))?;

        println!("Mensaje recibido message: {}", message);
        println!("Mensaje recibido data: {}", parsed_message.eventId);
        println!("Mensaje recibido nombre: {}", parsed_message.data.nombre);

        Ok(parsed_message)
    }
}