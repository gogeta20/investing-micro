use serde::{Deserialize, Serialize};

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
    pub numero_pokedex: f64,
    pub nombre: String,
    pub peso: f64,
    pub altura: f64,
    pub estadisticas: Estadisticas,
}

#[derive(Serialize, Deserialize, Debug)]
pub struct DataCollection {
    pub ps: f32,
    pub ataque: f32,
    pub defensa: f32,
    pub velocidad: f32,
    pub nombre: String,
    pub numero_pokedex: u32,
    pub peso: f32,
    pub altura: f32,
}

impl From<Data> for DataCollection {
    fn from(data: Data) -> Self {
        Self {
            ps: data.estadisticas.ps as f32,
            ataque: data.estadisticas.ataque as f32,
            defensa: data.estadisticas.defensa as f32,
            velocidad: data.estadisticas.velocidad as f32,
            nombre: data.nombre,
            numero_pokedex: data.numero_pokedex as u32,
            peso: data.peso as f32,
            altura: data.altura as f32,
        }
    }
}