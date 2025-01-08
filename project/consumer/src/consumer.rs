use lapin::{options::*, types::FieldTable, Connection, ConnectionProperties};
use log::info;

pub async fn start_consumer(amqp_url: &str) -> Result<(), Box<dyn std::error::Error>> {
    let conn = Connection::connect(amqp_url, ConnectionProperties::default().with_tokio()).await?;
    let channel = conn.create_channel().await?;
    info!("Conexión establecida con RabbitMQ");

    let queue_name = "events";

    channel.queue_declare(
        queue_name,
        QueueDeclareOptions::default(),
        FieldTable::default(),
    ).await?;

    let mut consumer = channel.basic_consume(
        queue_name,
        "consumer_tag",
        BasicConsumeOptions::default(),
        FieldTable::default(),
    ).await?;

    info!("Esperando mensajes en la cola '{}'", queue_name);

    while let Some(delivery) = consumer.next().await {
        let (_, delivery) = delivery.expect("error al recibir mensaje");
        let message = std::str::from_utf8(&delivery.data)?;
        rabbit::handler::handle_message(message).await?;
        delivery.ack(BasicAckOptions::default()).await?;
    }

    Ok(())
}
