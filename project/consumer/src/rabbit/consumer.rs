use lapin::{
    options::*,
    types::FieldTable,
    Connection,
    ConnectionProperties,
    Channel,
};
use futures_util::StreamExt;
use uuid::Uuid;

pub struct RabbitConsumer {
    channel: Channel,
    queue_name: String,
}

impl RabbitConsumer {
    pub async fn new(amqp_addr: &str, queue_name: &str) -> Result<Self, lapin::Error>
    {
        let conn = Connection::connect(amqp_addr, ConnectionProperties::default()).await?;
        let channel = conn.create_channel().await?;

        Ok(Self {
            channel,
            queue_name: queue_name.to_string(),
        })
    }

    pub async fn start_consuming(&self) -> Result<(), lapin::Error>
    {
        let consumer_tag = format!("rust_consumer_{}", Uuid::new_v4());

        let mut consumer = self.channel
            .basic_consume(
                &self.queue_name,
                &consumer_tag,
                BasicConsumeOptions::default(),
                FieldTable::default(),
            )
            .await?;

        println!("Comenzando a consumir mensajes...");

        while let Some(delivery) = consumer.next().await {
            match delivery {
                Ok(delivery) => {
                    self.handle_message(&delivery).await?;
                }
                Err(err) => {
                    println!("Error al recibir el mensaje: {:?}", err);
                }
            }
        }

        Ok(())
    }

    async fn handle_message(&self, delivery: &lapin::message::Delivery) -> Result<(), lapin::Error> {
        let message = String::from_utf8_lossy(&delivery.data);
        println!("Mensaje recibido: {}", message);

        delivery
            .ack(BasicAckOptions::default())
            .await
    }
}