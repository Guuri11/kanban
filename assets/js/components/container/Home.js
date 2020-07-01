import React, {useEffect, useState} from 'react';
import HomePresentational from "../presentational/Home";
import isAuth from "../api/User/isAuth";
import {Redirect, withRouter} from 'react-router-dom';
import getInfoUser from "../api/User/getInfoUser";
import getToken from "../api/User/getToken";
import getTables from "../api/Table/getTables";
import newTable from "../api/Table/newTable";
import deleteAccount from "../api/User/deleteAccount";
import deleteTable from "../api/Table/deleteTable";
import editTable from "../api/Table/editTable";

function Home(props) {

    const [user, setUser] = useState({});
    const [token, setToken] = useState('');
    const [tables, setTables] = useState([]);
    const [table_changed, setTableChanged] = useState(false);
    const [loading, setLoading] = useState(true);
    const [ submited, setSubmit ] = useState(false);
    const [ success, setSuccess ] = useState(false);
    const [ sending, setSending ] = useState(false);
    const [ error, setError ] = useState('');
    const [ errorDeleteTable, setErrorDeleteTable ] = useState('');
    const [ errorDeleteAccount, setErrorDeleteAccount ] = useState('');
    const [ errorEditTable, setErrorEditTable ] = useState('');
    const [ table_section, setTableSection ] = useState('show');


    useEffect( () => {
        // Check if user is logged
        if (sessionStorage.getItem('auth') === null)
            isAuth().then(r => {
                sessionStorage.setItem('auth', r.data.success);
            })
        if (sessionStorage.getItem('auth') === 'true'){
            getInfoUser().then(r => {
                if (r.data.success)
                    setUser(r.data.results);

            }).catch(e => props.history.push('/login'));

            getToken().then(r => {
                if (r.data.success)
                    setToken(r.data.results);
            }).catch(e => props.history.push('/login'));


        }
        setLoading(false);
    }, [] )

    useEffect( () => {
        getTables().then(r => {
            if (r.data.success)
                setTables(r.data.results);
        }).catch(e => {
            if (e.response.status === 401)
                props.history.push('/login')
        })
    }, [table_changed] )

    const handleSubmitNewTable = e => {
        e.preventDefault();

        setError('')
        setSuccess(false)
        setSubmit(true)
        setSending(true)
        const table = document.getElementById('table').value;

        const requestOptions = { name: table, token: token };

        newTable(requestOptions).then(r => {
            if (r.data.success){
                let tables_aux = tables;
                tables_aux.push(r.data.results);
                setTables(tables_aux);
                setSuccess(true);
            }
            else
                setError('No se pudo crear la tabla')
        }).catch(e => setError('No se pudo crear la tabla'));

        setSending(false)
    }

    const handleDeleteAccount = () => {
        const ans = confirm('¿Estás seguro de que quieres borrar la cuenta?')

        if (ans) {
            const requestOptions = { data: {token: token} };
            deleteAccount(requestOptions).then(r => {
                if (r.data.success === true) {
                    sessionStorage.removeItem('auth');
                    props.history.push('/');
                }
            }).catch(e => setErrorDeleteAccount(e.response.data.message))
        }
    }

    const handleDeleteTable = (id_table, id) => {

        const ans = confirm('¿Estás seguro de que quieres borrar la tabla?')

        if (ans) {
            const requestOptions = { data: {token: token} };
            deleteTable(id_table, requestOptions).then(r => {
                if (r.data.success){
                    let tables_aux = tables;
                    tables_aux.splice(id, 1);
                    setTables(tables_aux);
                    setTableChanged(!table_changed);
                }
            }).catch(e => setErrorDeleteTable(e.response.data.message));

        }
    }

    const handleEditTable = (e, id_table, id) => {
        e.preventDefault();

        const table_name = document.getElementById('table-'+id).value;

        const requestOptions = { name: table_name, image: null, token: token };

        editTable(id_table, requestOptions)
            .then(r =>{
                if (r.data.success){
                    setTableSection('show');
                    setTableChanged(!table_changed);
                }
            })
            .catch(e => setErrorEditTable('No se pudo editar la tabla'));

    }

    return (
        loading ?
            null
            :
            sessionStorage.getItem('auth') === "true" ?
                <HomePresentational
                    submited={submited} error={error} success={success} sending={sending}
                    username={user.username} tables={tables} handleSubmitNewTable={handleSubmitNewTable}
                    handleDeleteAccount={handleDeleteAccount} handleDeleteTable={handleDeleteTable}
                    errorDeleteTable={errorDeleteTable} errorDeleteAccount={errorDeleteAccount}
                    table_section={table_section} setTableSection={setTableSection} handleEditTable={handleEditTable}
                    errorEditTable={errorEditTable}
                    />
                :
                <Redirect to={'/login'}/>
    )
}

export default withRouter(Home);