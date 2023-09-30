import Surreal from "https://cdn.jsdelivr.net/npm/surrealdb.js";
import {Subject} from 'rxjs'

const db = new Surreal();
// Connect to the database
await db.connect( import.meta.env.VITE_RPC_HOST?import.meta.env.VITE_RPC_HOST:'http://localhost:8000/rpc', {
  // Set the namespace and database for the connection
  ns: 'unify',
  db: 'unify',
  // Set the authentication details for the connection
  auth: {
    user: 'viewer',
    pass: '123456',
  },
});


export {
  db
}



