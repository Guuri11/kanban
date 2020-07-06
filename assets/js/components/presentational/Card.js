import React from 'react';

export default function Card(props) {
    return (
        <div className="card" draggable={"true"} onDragStart={props.dragStart} id={props.id}>
            {props.name}
        </div>
    )
}