import React from 'react';

export default function Card(props) {

    const {task} = props;

    return (
        <>
            <div className="card" draggable={"true"} onDragStart={props.dragStart} id={props.id} data-toggle="modal" data-target={"#modal-"+task.id}>
                {task.name}
            </div>
            <div className="modal fade" id={"modal-"+task.id} tabIndex="-1" role="dialog" aria-labelledby="title-modal" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header d-block">
                            <div className="d-block w-100">
                                <span className="modal-title font-weight-bolder text-uppercase pointer" id="title-modal">{task.name}</span>
                                <span className="float-right form-check-inline">
                                    <label className="form-check-label mr-1" htmlFor={"task-finished-"+task.id}>Terminada</label>
                                    <input className="form-check-input" type="checkbox" value="" id={"task-finished-"+task.id}/>
                                </span>
                            </div>
                            <div className="float-right d-block mt-2 mr-3">
                                Terminada el 23/04/05
                            </div>
                        </div>
                        <div className="modal-body pointer">
                            {
                                task.description === "" ?
                                    "Añade una descripción..."
                                    :
                                    task.description
                            }
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" className="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}