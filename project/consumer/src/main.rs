mod config;
mod rabbit;
mod consumer;
use crate::consumer::application::use_case::listining_rabbit;
#[tokio::main]
async fn main() {
    listining_rabbit::start().await.expect("Error al escuchar rabbit");
}