import React, {useState} from 'react';
import {Link} from "react-router-dom";
import Header from "../container/Header";
import Footer from "../presentational/Footer";
import RegisterPresentational from "../presentational/Register";
import login from "../api/User/login";
import register from "../api/User/register";
import LoginPresentational from "../presentational/Login";

export default function Register() {

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

        register(requestOptions)
            .then(res => {
                if (res.data.success)
                    setSuccess(true);
                else
                    setError('Datos no válidos');
            })
            .catch(e => setError('Datos no válidos'));

        sessionStorage.setItem('auth', true)
        setSending(false);
    }


    return (
        <RegisterPresentational submited={submited} error={error} success={success} sending={sending} handleSubmit={handleSubmit}/>
    )
}