use dotenv::dotenv;
use std::env;

pub fn get_amqp_addr() -> String {
    dotenv().ok();
    env::var("AMQP_ADDR").expect("AMQP_ADDR must be set mau")
}
