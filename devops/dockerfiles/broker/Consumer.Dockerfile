FROM rust:1.74

WORKDIR /app
# COPY . .
COPY project/consumer .
RUN cargo build --release

CMD ["/app/target/release/rabbit_consumer"]
