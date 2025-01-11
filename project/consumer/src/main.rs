mod config;
mod rabbit;

use rabbit::RabbitConsumer;

#[tokio::main]
async fn main() {
    let rabbit_amqp = config::get_amqp_addr();

    match RabbitConsumer::new(&rabbit_amqp, "messages").await {
        Ok(rabbit) => {
            if let Err(e) = rabbit.start_consuming().await {
                println!("Error durante el consumo: {:?}", e);
            }
        }
        Err(e) => {
            println!("Error al conectar a RabbitMQ: {:?}", e);
        }
    }
}