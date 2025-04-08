# FROM rust:1.74
# WORKDIR /app

# - no     COPY . .
# - no     RUN useradd -m rustuser
# - no     USER rustuser

# COPY project/consumer .
# RUN cargo build --release
# CMD ["/app/target/release/rabbit_consumer"]
FROM rust:1.81 as builder
WORKDIR /app/src
COPY project/consumer/ ./
RUN cargo build --release

# FROM rust:1.74-slim
FROM rust:1.76-slim

WORKDIR /app
RUN mkdir -p /app/src
COPY --from=builder /app/src/target/release/rust_micro_app /app/rust_micro_app
COPY --from=builder /app/src /app/src

EXPOSE 9080

CMD ["/app/rust_micro_app"]
# COPY project/consumer/target/release/rabbit_consumer /app/rabbit_consumer
# COPY project/consumer/target/release/rust_micro_app /app/rust_micro_app
# CMD ["/app/rabbit_consumer"]
# CMD ["/app/rust_micro_app"]
# Copiar el ejecutable

# Exponer el puerto HTTP (si lo tenés)
# EXPOSE 9080
# CMD ["/app/ruzst_micro_app"]
