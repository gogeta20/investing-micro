db = db.getSiblingDB('admin');

db.auth('root', 'password');

db = db.getSiblingDB('pokemondb');

db.createUser({
    user: "admin",
    pwd: "admin_password",
    roles: [{ role: "readWrite", db: "pokemondb" }]
});
