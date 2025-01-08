use log::info;

pub async fn handle_message(message: &str) -> Result<(), Box<dyn std::error::Error>> {
    info!("Procesando mensaje: {}", message);
    // Aquí implementas la lógica del procesamiento
    Ok(())
}
