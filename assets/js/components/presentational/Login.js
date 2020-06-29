import React from 'react';
import Header from "../container/Header";
import Loading from "./Sending";
import {Link, Redirect} from "react-router-dom";
import Footer from "./Footer";

export default function LoginPresentational(props) {
    return (
        <div>
            <Header/>
            <div className="container login-container">
                <div className="login-form-1">
                    <h3>Iniciar sesión</h3>
                    {
                        props.sending ?
                            <Loading/>
                            :
                            null
                    }
                    {
                        props.submited && props.success ?
                            <Redirect to={'/'}/>
                            :
                            <h5 className={"text-center text-danger"}>{props.error}</h5>
                    }

                    <form onSubmit={props.handleSubmit}>
                        <div className="form-group">
                            <input type="text" className="form-control" id={"username"} placeholder="Usuario"/>
                        </div>
                        <div className="form-group">
                            <input type="password" className="form-control" id={"password"} placeholder="Contraseña"/>
                        </div>
                        <div className="form-group text-center">
                            <button type={"submit"} className={"btnSubmit"}>Enviar</button>
                        </div>
                        <div className="form-group text-center">
                            <Link to={'/registrarse'} className="no-registered">¿No tienes cuenta? ¡Registrate!</Link>
                        </div>
                    </form>
                </div>
            </div>
            <Footer/>
        </div>
    )
}