import React from 'react';
import {Link} from "react-router-dom";

export default function HeaderPresentational(props) {
    return (
        <div
            className="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-primary text-white shadow-sm">
            <h2 className="my-0 mr-md-auto font-weight-bolder">SOFTPROD</h2>
            <nav className="my-2 my-md-0 mr-md-3">
                <Link to={'/'} className="p-2 text-white">VER TABLAS</Link>
                {
                    props.logued === "true" ?
                        <Link to={'/'} onClick={ props.handleLogout } className="p-2 text-white">CERRAR SESIÓN</Link>
                        :
                        <>
                        <Link to={'/login'} className="p-2 text-white">INICIAR SESIÓN</Link>
                        <Link to={'/registrarse'} className="p-2 text-white">CREAR CUENTA</Link>
                        </>
                }
            </nav>
        </div>
    )
}