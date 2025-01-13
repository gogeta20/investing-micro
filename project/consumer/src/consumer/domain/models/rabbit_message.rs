use serde::Deserialize;

#[derive(Deserialize, Debug)]
pub struct RabbitMessage {
    pub data: Data,
    pub eventId: String,
}


#[derive(Deserialize, Debug)]
pub struct Estadisticas {
    pub ps: f64,
    pub ataque: f64,
    pub defensa: f64,
    pub velocidad: f64,
}

#[derive(Deserialize, Debug)]
pub struct Data {
    #[serde(rename = "0")]
    pub numero_pokedex: u32,
    #[serde(rename = "1")]
    pub nombre: String,
    #[serde(rename = "2")]
    pub peso: f64,
    #[serde(rename = "3")]
    pub altura: f64,
    pub estadisticas: Estadisticas,
}