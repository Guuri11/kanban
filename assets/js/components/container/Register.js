import React from 'react';
import {Link} from "react-router-dom";
import Header from "../presentational/Header";
import Footer from "../presentational/Footer";

export default function Login() {
    return (
        <div>
            <Header/>
            <h1>Register</h1>
            <Link to={'/'}>Home</Link>
            <Footer/>
        </div>
    )
}