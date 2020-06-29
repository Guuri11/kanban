import React from 'react';
import {Link} from "react-router-dom";
import Header from "../container/Header";
import Footer from "../presentational/Footer";

export default function HomePresentational() {

    return (
        <div>
            <Header/>
            <h1>Home</h1>
            <Link to={'/login'}>Login</Link>
            <Footer/>
        </div>
    )
}