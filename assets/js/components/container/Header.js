import React, {useEffect, useState} from 'react';
import HeaderPresentational from "../presentational/Header";
import isAuth from "../api/User/isAuth";
import logout from "../api/User/logout";

export default function Header() {

    useEffect( () => {
        if (sessionStorage.getItem('auth') === null)
            isAuth().then(r => {
                console.log(r.data);
                sessionStorage.setItem('auth', r.data.success);
            })
    }, [] )

    const handleLogout = () => {
        logout();
        sessionStorage.setItem('auth', false)
    }

    return <HeaderPresentational logued={sessionStorage.getItem('auth')} handleLogout={handleLogout}/>
}