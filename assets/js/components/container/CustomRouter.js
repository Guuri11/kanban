import React from 'react';
import {BrowserRouter as Router, Route, Switch} from "react-router-dom";
import Login from "./Login";
import Register from "./Register";
import Home from "./Home";
import Table from "./Table";

export default function CustomRouter() {

    return (
        <Router>
            <Switch>
                <Route exact path="/login" component={Login}/>
                <Route exact path="/registrarse" component={Register}/>
                <Route exact path="/tabla/:id" component={Table}/>
                <Route exact path="/" component={Home}/>
                <Route component={Home}/>
            </Switch>
        </Router>
    )

}