pub fn log_error(error: &dyn std::error::Error) {
    eprintln!("Error: {}", error);
}
