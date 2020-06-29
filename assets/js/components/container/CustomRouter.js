import React from 'react';
import {BrowserRouter as Router, Route, Switch} from "react-router-dom";
import Login from "./Login";
import Register from "./Register";
import Home from "./Home";

export default function CustomRouter() {

    return (
        <Router>
            <Switch>
                <Route exact path="/login" component={Login}/>
                <Route exact path="/registrarse" component={Register}/>
                <Route exact path="/" component={Home}/>
            </Switch>
        </Router>
    )

}