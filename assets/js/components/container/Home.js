import React from 'react';
import {Link} from "react-router-dom";
import Header from "../presentational/Header";
import Footer from "../presentational/Footer";
import isAuth from "../api/User/isAuth";
import getToken from "../api/User/getToken";

export default function Home() {

    isAuth();
    getToken();

    return (
        <div>
            <Header/>
            <h1>Home</h1>
            <Link to={'/login'}>Login</Link>
            <Footer/>
        </div>
    )
}