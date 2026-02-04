CREATE TABLE inventario (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo_barra_producto VARCHAR(13) NOT NULL UNIQUE,
    nombre_producto VARCHAR(150) NOT NULL,
    descripcion_producto VARCHAR(255),
    id_categoria INT NOT NULL,
    precio_compra DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    estado_producto TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_inventario_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categoria(id_categoria)
);

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion_categoria VARCHAR(255)
);

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_persona VARCHAR(100) NOT NULL,
    apellido_persona VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena_usuario VARCHAR(255) NOT NULL,
    rol_usuario char(3) DEFAULT NULL COMMENT 'Tipo de Usuario, Normal, Consultor o Cliente',
    estado_usuario BOOLEAN NOT NULL DEFAULT TRUE
);

