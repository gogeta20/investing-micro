CREATE TABLE payment (
    id VARCHAR(36) PRIMARY KEY, -- UUID generado desde PHP
    order_id VARCHAR(255) NOT NULL, -- ID de la orden en la pasarela de pago
    approve_link VARCHAR(255) NOT NULL, -- ID de la orden en la pasarela de pago
    type ENUM('one-time', 'subscription', 'refund') NOT NULL, -- Tipo de pago
    platform ENUM('paypal', 'stripe', 'mercadopago') NOT NULL, -- Plataforma de pago
    amount DECIMAL(10,2) NOT NULL, -- Monto del pago con dos decimales
    currency VARCHAR(3) NOT NULL, -- Código de moneda (ej. USD, EUR)
    customer_email VARCHAR(255) NOT NULL, -- Email del cliente
    status ENUM('CREATED', 'APPROVED', 'FAILED', 'REFUNDED') NOT NULL, -- Estado del pago
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Fecha de creación
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Última actualización
);
