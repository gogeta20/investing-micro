db = db.getSiblingDB('admin');

db.auth('root', 'password');

db = db.getSiblingDB('intents_db');

db.createUser({
    user: "admin",
    pwd: "admin_password",
    roles: [{ role: "readWrite", db: "intents_db" }]
});

db.createCollection("intents");
