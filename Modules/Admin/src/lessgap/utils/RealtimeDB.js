import Surreal from "./surrealdb.js";

const db = new Surreal();
db.connect(import.meta.env.VITE_RPC_HOST ? import.meta.env.VITE_RPC_HOST : 'http://localhost:8000/rpc', {
  ns: 'unify',
  db: 'unify',
  auth: {
    user: 'viewer',
    pass: '123456',
  },
});


export {
  db
}



