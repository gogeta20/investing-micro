# FROM rust:1.74
# WORKDIR /app

# - no     COPY . .
# - no     RUN useradd -m rustuser
# - no     USER rustuser

# COPY project/consumer .
# RUN cargo build --release
# CMD ["/app/target/release/rabbit_consumer"]


FROM rust:1.74-slim
WORKDIR /app
COPY project/consumer/target/release/rabbit_consumer /app/rabbit_consumer
CMD ["/app/rabbit_consumer"]
