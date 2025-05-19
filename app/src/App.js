import logo from './logo.svg';
import './App.css';
import { use, useState } from 'react';
import AlunnoRow from './AlunnoRow';

function App() {

  const [loading, setLoading] = useState(false);      // Variabile di stato sul caricamento
  const [alunni, setAlunni] = useState([]);           // Variabile di stato sugli alunni mostrati nella pagina
  const [inserting, setInserting] = useState(false);

  const [nome, setNome] = useState("");
  const [cognome, setCognome] = useState("");

  /*
  function carica() {
    setLoading(true);
    fetch("http://localhost:8080/alunni")             // Funzione fetch --> invia una richiesta all'URL indicato
    .then(response => response.json())                // .then(...) --> Eseguita quando termina la fetch (fino al ;)
    .then(data => {                                   // In questo caso si aspetta di aver finito la fetch poi si codifica in json quello che si riceve e si chiama la funzione che si occupa della tabella
    setAlunni(data)
    setLoading(false)});
    console.log("alunni caricati correttamente");
  }
  */
  
  async function carica() {
    setLoading(true);
    const response = await fetch("http://localhost:8080/alunni");  
    const data = await response.json();           // Funzione fetch --> invia una richiesta all'URL indicato                              // In questo caso si aspetta di aver finito la fetch poi si codifica in json quello che si riceve e si chiama la funzione che si occupa della tabella
    setAlunni(data);
    setLoading(false);
    console.log("alunni caricati correttamente");
  }

  function mostraForm() {
    setInserting(true);
  }

  async function salvaAlunno() {
    console.log(nome + " " + cognome);
    setInserting(false);
    setLoading(true);
    const response = await fetch("http://localhost:8080/classi/2/alunni", {
      method: "POST",
      headers: {
        "Content-Type":"application/json"  
      },
      body : JSON.stringify({id_classe:2, nome: nome, cognome: cognome})
    })
    const data = await response.json(); 
    carica();
  }

  async function eliminaAlunno(id) {
    setInserting(false);
    setLoading(true);
    const response = await fetch("http://localhost:8080/classi/2/alunni/" + id, {
      method: "DELETE"
    })
    const data = await response.json(); 
    carica();
    console.log("Elminato alunno: " + id);
  }

  return (
    <div className="App">
      {alunni.length > 0 &&
        <table>
          <thead>
            <tr>
              <th> ID </th>
              <th> NOME </th>
              <th> COGNOME </th>
              <th/>
            </tr>
          </thead>
          <tbody>
          {
            alunni.map(alunno => 
              <AlunnoRow alunno={alunno} elimina={(id) => eliminaAlunno(id)}/>
            )
          }
          </tbody>
        </table>
      }
      {loading && <p> Caricamento... </p>}
      <br />
      {alunni.length == 0 && !loading && <button onClick={carica}> CARICA </button>}
      {alunni.length > 0 && !inserting && <button onClick={mostraForm}> AGGIUNGI </button>}
      {inserting && 
        <div>
          <div id='alunno_insert_form'>
            <label> Nome: </label>
            <input type='text' name='alunnoName' onChange={(e) => setNome(e.target.value)}/> <br/>
            <label> Cognome: </label>
            <input type='text' name='alunnoSurname' onChange={(e) => setCognome(e.target.value)}/> <br />
            <button onClick={salvaAlunno}>AGGIUNGI</button>
            <button onClick={() => setInserting(false)}>ANNULLA</button>
          </div>
        </div>}
    </div>
  );
}

export default App;
