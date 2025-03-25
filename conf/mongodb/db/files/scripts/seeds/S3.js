use pokemondb;

db.createCollection("payments")

db.payments.insertOne({
  _id: ObjectId(),
  order_id: "PAYPAL123456",
  type: "one-time",
  platform: "paypal",
  amount: 10.99,
  currency: "USD",
  customer_email: "cliente@example.com",
  status: "CREATED",
  created_at: new Date(),
  updated_at: new Date(),
  metadata: {
    approval_url: "https://www.sandbox.paypal.com/checkoutnow?token=PAYPAL123456"
  }
})

db.payments.find().pretty()

db.payments.find({ platform: "paypal" }).pretty()



// test> show dbs
// admin       148.00 KiB
// config      108.00 KiB
// intents_db  128.00 KiB
// local       104.00 KiB
// pokemondb   112.00 KiB
// test         92.00 KiB
// test> use pokemondb
// switched to db pokemondb
// pokemondb> show collections
// pokemon_base_view
// pokemondb> load("/var/www/html/scripts/seeds/S3.js")
// true
// pokemondb> show collections
// payments
// pokemon_base_view
// pokemondb> db.payments.find({ platform: "paypal" }).pretty()
// [
//   {
//     _id: ObjectId('67da68199465af666c544ca8'),
//     order_id: 'PAYPAL123456',
//     type: 'one-time',
//     platform: 'paypal',
//     amount: 10.99,
//     currency: 'USD',
//     customer_email: 'cliente@example.com',
//     status: 'CREATED',
//     created_at: ISODate('2025-03-19T06:45:45.152Z'),
//     updated_at: ISODate('2025-03-19T06:45:45.152Z'),
//     metadata: {
//       approval_url: 'https://www.sandbox.paypal.com/checkoutnow?token=PAYPAL123456'
//     }
//   }
// ]
// pokemondb>
