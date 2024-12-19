db = db.getSiblingDB('admin'); // Cambiar a la base de datos admin

db.auth('root', 'password'); // Autenticarse como root

db = db.getSiblingDB('intents_db'); // Cambiar a la base de datos de trabajo

db.createUser({
    user: "admin",
    pwd: "admin_password",
    roles: [{ role: "readWrite", db: "intents_db" }]
});

db.createCollection("intents");
