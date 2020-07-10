import React from 'react';

export default function Card(props) {

    const {task} = props;

    return (
        <>
            <div className="card" draggable={"true"} onDragStart={props.dragStart} id={task.id} data-toggle="modal" data-target={"#modal-"+task.id}>
                {task.name}
            </div>
            <div className="modal fade" id={"modal-"+task.id} data-backdrop="static" data-keyboard="false" tabIndex="-1"
                 role="dialog" aria-labelledby="title-modal" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header d-block">
                            <div className="d-block w-100">
                                {
                                    props.select_edit_task_name ?
                                        <form className="modal-title d-inline" onSubmit={e => props.handleEditTask(e,task)}>
                                            <input type={'text'} id={'edit-task-name-'+task.id} defaultValue={task.name}/>
                                            <button type={'submit'} className="ml-1 btn btn-success btn-sm"><i className="fa fa-save"/></button>
                                        </form>
                                        :
                                        <span className="modal-title font-weight-bolder text-uppercase pointer"
                                              onClick={() => props.setEditTaskName(true)} id="title-modal">{task.name}</span>
                                }
                                <span className="float-right form-check-inline">
                                    <form>
                                        <label className="form-check-label mr-1" htmlFor={"task-finished-"+task.id}>Terminada</label>
                                        <input className="form-check-input" onClick={e => props.handleEditTask(e,task) } defaultChecked={task.finished} type="checkbox" value="" id={"task-finished-"+task.id}/>
                                    </form>
                                </span>
                            </div>
                            <div className="float-right d-block mt-2 mr-3">
                                Terminada el 23/04/05
                            </div>
                        </div>
                        {
                            props.select_edit_task_description ?
                                <div className="modal-body">
                                    <form onSubmit={e => props.handleEditTask(e,task)}>
                                        <textarea className="w-100" rows={10} id={'edit-task-description-'+task.id} defaultValue={task.description}
                                        onChange={e => props.setDescriptionTask(e.target.value)}/>
                                        <button type={'submit'} className="ml-1 btn btn-success btn-sm"><i className="fa fa-save"/> Guardar descripción</button>
                                    </form>
                                </div>
                                :
                                <div className="modal-body pointer" onClick={() => props.setEditTaskDescription(true)}>
                                    {
                                        task.description === "" ?
                                            "Añade una descripción..."
                                            :
                                            task.description
                                    }
                                </div>
                        }

                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary"
                                    onClick={() => {props.setEditTaskDescription(false); props.setEditTaskName(false)}} data-dismiss="modal">Cerrar</button>
                            <button type="button" className="btn btn-danger"><i className="fa fa-trash"/></button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}