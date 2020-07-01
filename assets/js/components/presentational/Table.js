import React from 'react';
import Header from "../container/Header";
import Footer from "./Footer";

export default function TablePresentational(props) {

    return (
        <div>
            <Header/>
            {props.table.name}
            <Footer/>
        </div>)
}