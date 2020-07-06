import React from 'react';
import Card from "./Card";

export default function Column(props) {
    return (
        <div className="column-area">
            <div className="board-list">
                <div className="list-title">
                    {props.column.title}
                </div>
                <div className="list-body p-1" id={props.id} onDrop={props.dropIt} onDragOver={props.allowDrop}>
                    {
                        props.tasks.length > 0 ?
                            props.tasks.map( (task, idx) => {
                                return (
                                    <Card key={idx} dragStart={props.dragStart} id={task.id} name={task.name}/>
                                )
                            } )
                            :
                            null
                    }
                </div>
                <button className="btn btn-secondary text-left">+ Añade otro posit</button>
            </div>
        </div>
    )
}