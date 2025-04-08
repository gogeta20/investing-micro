db = db.getSiblingDB('intents_db');

db.intents_restaurant.drop();

db.intents_restaurant.insertMany([
  { intent: "reservar_mesa", response: "¿Cuántas personas serán para la reserva?" },
  { intent: "confirmar_personas", response: "¿A qué hora te gustaría reservar?" },
  { intent: "confirmar_horario", response: "Perfecto, ¿confirmas la reserva para las 15:00 para 4 personas?" },
  { intent: "confirmar_reserva", response: "Reserva realizada, ¡te esperamos!" }
]);
