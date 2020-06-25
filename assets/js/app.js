import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import '../css/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import Header from "./components/presentational/Header";
import Footer from "./components/presentational/Footer";
import { createStore, combineReducers } from "redux";
import { Provider, useSelector, useDispatch } from "react-redux";
import Login from "./components/container/Login";
import Home from "./components/container/Home";
import Register from "./components/container/Register";


ReactDOM.render(
    <Router>
        <Switch>
            <Route exact path="/login" component={Login}/>
            <Route exact path="/registrarse" component={Register}/>
            <Route exact path="/" component={Home}/>
        </Switch>
    </Router>
    ,document.getElementById('root'));