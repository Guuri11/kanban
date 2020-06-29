import React, {useEffect, useState} from 'react';
import HomePresentational from "../presentational/Home";
import isAuth from "../api/User/isAuth";
import LoginPresentational from "../presentational/Login";
import login from "../api/User/login";

export default function Home() {

    const [ submited, setSubmit ] = useState(false);
    const [ success, setSuccess ] = useState(false);
    const [ sending, setSending ] = useState(false);
    const [ error, setError ] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();

        setSending(true);
        setSubmit(true);

        // Get form data
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        const requestOptions = { username: username, password: password };

        login(requestOptions)
            .then(res => {
                if (res.data.success)
                    setSuccess(true);
                else
                    setError('Usuario o contraseña no correctos');
            })
            .catch(e => setError('Usuario o contraseña no correctos'));

        sessionStorage.setItem('auth', true)
        setSending(false);
    }

    useEffect( () => {
        if (sessionStorage.getItem('auth') === null)
            isAuth().then(r => {
                console.log(r.data);
                sessionStorage.setItem('auth', r.data.success);
            })
    }, [] )

    return (
            sessionStorage.getItem('auth') === "true" ?
                <HomePresentational/>
                :
                <LoginPresentational submited={submited} error={error} success={success} sending={sending} handleSubmit={handleSubmit}/>
    )
}