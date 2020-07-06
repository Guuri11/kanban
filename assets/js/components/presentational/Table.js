import React from 'react';
import Header from "../container/Header";
import Footer from "./Footer";
import Card from "./Card";
import Column from "./Column";

export default function TablePresentational(props) {

    const tasks = [
        {id:1, name:"Tarea 1"},
        {id:2, name:"Tarea 2"},
        {id:3, name:"Tarea 3"},
        {id:4, name:"Tarea 4"},
        {id:5, name:"Tarea 5"},
    ]

    return (
        <div>
            <Header/>
            <div className="base">
                <div className="container-fluid">
                    <div className="row">
                        <div className="col-1">
                            <h4 className="font-weight-bolder">{props.table.name}</h4>
                        </div>
                        <div className="col">
                            <button className="btn btn-primary"> + </button>
                        </div>
                    </div>
                    <div className="board mt-3">
                        <div className="board-lists">
                            <Column id={'column-1'} dropIt={props.dropIt} allowDrop={props.allowDrop} dragStart={props.dragStart} tasks={tasks} column={{title: "To do"}}/>
                            <Column id={'column-2'} dropIt={props.dropIt} allowDrop={props.allowDrop} dragStart={props.dragStart} tasks={[]} column={{title: "In process"}}/>
                            <Column id={'column-3'} dropIt={props.dropIt} allowDrop={props.allowDrop} dragStart={props.dragStart} tasks={[]} column={{title: "Done"}}/>
                            <div className="board-list" id='list3'>
                                <button className="btn btn-secondary text-left">+ Añade otra columna</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Footer/>
        </div>)
}