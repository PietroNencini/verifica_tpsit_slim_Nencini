import { useState } from 'react';

function AlunnoRow(props) {
    const alunno = props.alunno;
    const [deleting, setDeleting] = useState(false); // Stato specifico per ogni alunno

    function gestisciEliminazione() {
        setDeleting(false);
        props.elimina(alunno.id);
    }

    return (
        <tr key={alunno.id}>
            <td> {alunno.id} </td>
            <td> {alunno.nome} </td>
            <td> {alunno.cognome} </td>
            <td>
                {!deleting ? (
                    <button onClick={() => setDeleting(true)}> ELIMINA </button>
                ) : (
                    <>
                        <label> Eliminare? </label> <br/>
                        <button onClick={() => gestisciEliminazione()}> SÌ </button>
                        <button onClick={() => setDeleting(false)}> NO </button>
                    </>
                )}
            </td>
        </tr>
    );
}

export default AlunnoRow;