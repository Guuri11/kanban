import React from 'react';
import Header from "../container/Header";
import Footer from "./Footer";
import Card from "./Card";
import Column from "./Column";

export default function TablePresentational(props) {

    return (
        <div>
            <Header/>
            <div className="base">
                <div className="container-fluid">
                    <div className="row">
                        <div className="col-1">
                            <h4 className="font-weight-bolder">{props.table.name}</h4>
                        </div>
                        <div>
                            <button className="btn btn-sm btn-info float-right ml-3" onClick={ () => props.setChangeToEdit(false)}>
                                <i className="fa fa-eye text-white"/> Visualizar columnas
                            </button>
                            <button className="btn btn-sm btn-info float-right ml-3" onClick={ () => props.setChangeToEdit(true)}>
                                <i className="fa fa-edit text-white"/> Editar columnas
                            </button>
                            <button className="btn btn-sm btn-info float-right ml-3" onClick={() => props.setCreateTaskSelected(!props.createTaskSelected)}>
                                <i className="fa fa-tasks text-white"/> Crear tarea
                            </button>
                        </div>
                    </div>
                    <div className="board mt-3">
                        <div className="board-lists">
                            {
                                props.columns !== undefined && props.columns.length > 0 ?
                                    props.columns.map( (column, idx) => {
                                        return <Column key={idx} id={'column-'+idx+1} dropIt={props.dropIt}
                                                       allowDrop={props.allowDrop} dragStart={props.dragStart}
                                                       tasks={column.tasks} column={column} handleDeleteColumn={props.handleDeleteColumn}
                                                       idx_column={idx} changeToEdit={props.changeToEdit} handleEditColumn={props.handleEditColumn}
                                                       handleCreateTask={props.handleCreateTask} createTaskSelected={props.createTaskSelected}
                                                       select_edit_task_name={props.select_edit_task_name} setEditTaskName={props.setEditTaskName}
                                                       select_edit_task_description={props.select_edit_task_description}
                                                       setEditTaskDescription={props.setEditTaskDescription} handleEditTask={props.handleEditTask}
                                                       setDescriptionTask={props.setDescriptionTask}/>
                                    } )
                                    :
                                    null
                            }
                            {
                                props.createColumnSelected ?
                                    <div className="board-list" id='list3'>
                                        <button className="btn btn-secondary text-left" onClick={() => props.setCreateColumnSelected(false)}>
                                            + Añade otra columna
                                        </button>
                                        <form onSubmit={props.handleCreateColumn}>
                                            <div className="form-group">
                                                <input type="text" className="form-control" id={"column-add-name"} placeholder="Nombre de la columna..."/>
                                            </div>
                                            <div className="form-group">
                                                <button type={"submit"} className="btn btn-success">Crear</button>
                                            </div>
                                        </form>
                                    </div>
                                    :
                                    <div className="board-list" id='list3'>
                                        <button className="btn btn-secondary text-left" onClick={() => props.setCreateColumnSelected(true)}>
                                            + Añadir columna
                                        </button>
                                    </div>
                            }

                        </div>
                    </div>
                </div>
            </div>
            <Footer/>
        </div>)
}