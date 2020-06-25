import React from 'react';

export default function Header(props) {
    return (
        <div
            className="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-primary text-white border-bottom shadow-sm">
            <h2 className="my-0 mr-md-auto font-weight-bolder">SOFTPROD</h2>
            <nav className="my-2 my-md-0 mr-md-3">
                <a className="p-2 text-white" href="#">VER TABLAS</a>
                <a className="p-2 text-white" href="#">INICIAR SESIÓN</a>
            </nav>
        </div>
    )
}