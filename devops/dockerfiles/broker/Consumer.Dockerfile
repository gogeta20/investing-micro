FROM rust:1.74

WORKDIR /app
# COPY . .
# RUN useradd -m rustuser
# USER rustuser

COPY project/consumer .
RUN cargo build --release

CMD ["/app/target/release/rabbit_consumer"]
