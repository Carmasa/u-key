# U-Key - Tienda de Periféricos Gaming

Proyecto de tienda online desarrollada con Laravel para la asignatura de Desarrollo Web. Permite la gestión de catálogo, pedidos y usuarios, con un panel de administración completo.

## 🚀 Tecnologías Utilizadas

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade, Bootstrap 5, Bootstrap Icons
- **Base de Datos**: MySQL / MariaDB
- **Pagos**: Integración con Stripe (Mock)

## 📋 Funcionalidades

### Cliente
- Navegación por catálogo y categorías (Teclados, Ratones, Accesorios).
- Carrito de compras persistente.
- Proceso de Checkout integrado.
- Registro y Login de usuarios.
- **Área de Cliente**: Historial de pedidos con estados en tiempo real.

### Administrador
- **Dashboard de Pedidos**: Gestión de estados (Pendiente, Preparación, Enviado), filtrado y búsqueda.
- **Gestión de Productos**: CRUD completo con soporte para múltiples imágenes.
- **Documentación**: Generación de Packing List y Etiquetas de Envío.

## 🛠️ Instalación

1.  Clonar el repositorio:
    ```bash
    git clone https://github.com/tu-usuario/u-key.git
    ```
2.  Instalar dependencias de PHP y Node:
    ```bash
    composer install
    npm install
    ```
3.  Configurar entorno:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  Configurar base de datos en `.env` y migrar:
    ```bash
    php artisan migrate --seed
    ```
5.  Compilar assets y servir:
    ```bash
    npm run build
    php artisan serve
    ```

---

## 📊 Diseño del Proyecto

### Diagrama Entidad-Relación (ERD)

Estructura lógica de la base de datos. Nota: Los detalles de productos en los pedidos se almacenan como JSON para mantener un historial inmutable del precio/nombre en el momento de la compra.

```mermaid
erDiagram
    USUARIO ||--o{ PEDIDO : "realiza"
    USUARIO {
        bigint id PK
        string name
        string email
        string password
        string role "admin/user"
    }

    CATEGORIA ||--o{ PRODUCTO : "contiene"
    CATEGORIA {
        bigint id PK
        string nombre
        string slug
    }

    PRODUCTO ||--o{ FOTO_PRODUCTO : "tiene"
    PRODUCTO {
        bigint id PK
        string nombre
        text descripcion
        decimal precio
        integer stock
        bigint categoria_id FK
        boolean visible
    }

    FOTO_PRODUCTO {
        bigint id PK
        string url
        bigint producto_id FK
    }

    PEDIDO {
        bigint id PK
        string numero_pedido
        decimal total
        enum estado "pendiente, nuevo, en_preparacion, enviado"
        json productos "Snapshot de items"
        bigint usuario_id FK
    }
```

### Diagrama de Casos de Uso

Interacciones principales de los actores con el sistema.

```mermaid
graph LR
    User((Cliente))
    Admin((Administrador))

    subgraph "Tienda Pública"
        C1[Ver Catálogo]
        C2[Añadir al Carrito]
        C3[Realizar Pago/Checkout]
        C4[Registro / Login]
    end

    subgraph "Área Privada"
        P1[Ver Mis Pedidos]
        P2[Gestionar Perfil]
    end

    subgraph "Panel de Administración"
        A1[Gestionar Pedidos]
        A2[Cambiar Estados]
        A3[Imprimir Documentos]
        A4[CRUD Productos]
    end

    User --> C1
    User --> C2
    User --> C3
    User --> C4
    User --> P1
    User --> P2

    Admin --> A1
    Admin --> A2
    Admin --> A3
    Admin --> A4
    Admin --> C1
```