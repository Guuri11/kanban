import React from 'react';
import Card from "./Card";

export default function Column(props) {

    return (
        <div className="column-area">
            <div className="board-list">
                {
                    props.changeToEdit ?
                        <div className="list-title">
                            <form onSubmit={ e => props.handleEditColumn(e, props.column.id, props.idx_column)}>
                                <input type={'text'} defaultValue={props.column.name} id={'column-edit-name-'+props.idx_column}/>
                                <button type={"submit"} className="btn btn-success btn-sm float-right"><i className="fa fa-save"/></button>
                            </form>
                        </div>
                        :
                        <div className="list-title">
                            <span>
                                {props.column.name}
                            </span>
                            <button className="btn btn-danger btn-sm float-right" onClick={() => props.handleDeleteColumn(props.column.id, props.idx_column)}><i className="fa fa-trash"/></button>
                        </div>
                }
                <div className="list-body p-1 list-area" data-info={props.column.id} id={props.id} onDrop={props.dropIt} onDragOver={props.allowDrop}>
                    {
                        props.tasks.length > 0 ?
                            props.tasks.map( (task, idx) => {
                                return (
                                    <Card key={idx} dragStart={props.dragStart} task={task} select_edit_task_name={props.select_edit_task_name}
                                          setEditTaskName={props.setEditTaskName} select_edit_task_description={props.select_edit_task_description}
                                          setEditTaskDescription={props.setEditTaskDescription} handleEditTask={props.handleEditTask}
                                          setDescriptionTask={props.setDescriptionTask} handleDeleteTask={props.handleDeleteTask}/>
                                )
                            } )
                            :
                            null
                    }
                </div>
                {
                    props.createTaskSelected ?
                        <form onSubmit={e => props.handleCreateTask(e, props.idx_column ,props.column.id)}>
                            <div className="form-group">
                                <input type="text" className="form-control" id={"task-add-name-"+props.idx_column} placeholder="Nombre de la tarea..."/>
                            </div>
                            <div className="form-group">
                                <button type={"submit"} className="btn btn-success">Crear</button>
                            </div>
                        </form>
                        :
                        null
                }
            </div>
        </div>
    )
}