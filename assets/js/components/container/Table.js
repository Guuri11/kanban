import React, {useEffect, useState} from 'react';
import getTable from "../api/Table/getTable";
import TablePresentational from "../presentational/Table";
import getToken from "../api/User/getToken";
import newColumn from "../api/Column/newColumn";
import deleteColumn from "../api/Column/deleteColumn";
import editColumn from "../api/Column/editColumn";
import newTask from "../api/Task/newTask";
import swapTasks from "../api/Task/swapTasks";
import editTask from "../api/Task/editTask";
import deleteTask from "../api/Task/deleteTask";
import axios from "axios";
import editTable from "../api/Table/editTable";

export default function Table(props) {

    const [table, setTable] = useState({});
    const [columns, setColumns] = useState([]);
    const [column_changed, setColumnChanged] = useState(false);
    const [token, setToken] = useState('');
    const [createColumnSelected, setCreateColumnSelected] = useState(false);
    const [createTaskSelected, setCreateTaskSelected] = useState(false);
    const [change, setChange] = useState(false);
    const [changeToEdit, setChangeToEdit] = useState(false);
    const {id} = props.match.params
    const [ select_edit_task_name, setEditTaskName ] = useState(false);
    const [ select_edit_task_description, setEditTaskDescription ] = useState(false);
    const [ descriptionTask, setDescriptionTask ] = useState('');
    const [ image_input, setImageInput ] = useState('');
    const [ image_results, setImageResults ] = useState([]);

    useEffect( () => {
        getTable(id).then( r => {
            if (r.data.success){
                setTable(r.data.results);
                setColumns(r.data.results.columns);
            }
        } ).catch(e => props.history.push('/'))

        getToken().then(r => {
            if (r.data.success)
                setToken(r.data.results);
        }).catch(e => props.history.push('/login'));
    }, [column_changed] )

    const dropIt = e => {
        e.preventDefault();
        let sourceId = e.dataTransfer.getData("text/plain");
        let sourceIdEl=document.getElementById(sourceId);
        let sourceIdParentEl=sourceIdEl.parentElement;


        let targetEl=document.getElementById(e.target.id)
        let targetParentEl=targetEl.parentElement;

        const id_source = sourceIdEl.id
        const id_target = targetEl.id

        // different column
        if (targetParentEl.id!==sourceIdParentEl.id){

            // over a task
            if (targetEl.className === sourceIdEl.className){

                const requestOptions = { token: token, task_one: id_source, task_two: id_target, change_column: true, target_is_task: true }

                swapTasks(requestOptions).catch(e => props.history.push('/'))
            }
            // over the list
            else{
                const requestOptions = { token: token, task_one: id_source, column: targetEl.getAttribute('data-info'), change_column: true, target_is_task: false }
                swapTasks(requestOptions).catch(e => props.history.push('/'))
            }

        }
        // same column
        else {
            const id_source = sourceIdEl.id
            const id_target = targetEl.id

            const requestOptions = { token: token, task_one: id_source, task_two: id_target, change_column: false, target_is_task: false }

            swapTasks(requestOptions).catch(e => props.history.push('/'))

        }

        setColumnChanged(!column_changed);
    }

    const allowDrop = e => {
        e.preventDefault()
    }

    const dragStart = e => {
        e.dataTransfer.setData("text/plain", e.target.id);
    }

    const handleCreateColumn = e => {
        e.preventDefault();

        const name = document.getElementById('column-add-name').value;

        const requestOptions = { name: name, token: token, table: table.id };

        newColumn(requestOptions).then(r => {
            if (r.data.success){
                let columns_aux = columns;
                columns_aux.push(r.data.results);
                setColumns(columns_aux);
                setCreateColumnSelected(false)
            }
        })
            .catch(e=> props.history.push('/login'));
    }

    const handleDeleteColumn = (id, idx_column) => {

        const ans = confirm('¿Estás seguro de que quieres borrar la tabla?')

        if (ans) {
            const requestOptions = { data: { token: token } };

            deleteColumn(id, requestOptions).then(r => {
                if (r.data.success){
                    let columns_aux = columns;
                    columns_aux.splice(idx_column, 1);
                    setColumns(columns_aux);
                    setChange(!change);
                }
            }).catch(e => props.history.push('/'));
        }
    }

    const handleEditColumn = (e, id, idx_column) => {
        e.preventDefault();

        const name = document.getElementById('column-edit-name-'+idx_column).value;

        const requestOptions = { name: name, token: token };

        editColumn(id, requestOptions).then(r => {
            if (r.data.success){
                setChangeToEdit(false)
                setColumnChanged(!column_changed);
            }
        })
            .catch(e=> props.history.push('/login'));

    }

    const handleCreateTask = (e, idx,column) => {
        e.preventDefault();

        const name = document.getElementById('task-add-name-'+idx).value;

        const requestOptions = { name: name, token: token, column: column }

        newTask(requestOptions).then(r => {
            if (r.data.success){
                setColumnChanged(!column_changed);
                setCreateTaskSelected(false)
            }
        })
            .catch(e=> props.history.push('/'));
    }

    const handleDeleteTask = (id) => {

        const ans = confirm('¿Estás seguro de que quieres borrar la tarea?')

        if (ans) {
            const requestOptions = { data: { token: token } };

            deleteTask(id, requestOptions).then(r => {
                if (r.data.success){
                    setColumnChanged(!column_changed);
                }
            }).catch(e => props.history.push('/'));
        }
    }

    const handleEditTask = (e, task) => {
        if (e.type !== "click")
            e.preventDefault();

        const name = document.getElementById('edit-task-name-'+task.id) ? document.getElementById('edit-task-name-'+task.id).value:task.name;
        const description = document.getElementById('edit-task-description-'+task.id) ? document.getElementById('edit-task-description-'+task.id).value:task.description;
        const finished = document.getElementById('task-finished-'+task.id).checked;

        const requestOptions = { token: token, column: task.column, name: name, description: descriptionTask === '' ? description: descriptionTask, finished: finished };

        editTask(task.id, requestOptions).then(r => {
            if (r.data.success){
                setEditTaskDescription(false);
                setEditTaskName(false);
                setColumnChanged(!column_changed);
                setDescriptionTask('');
            }
        }).catch(e => props.history.push('/'));
    }

    const handleGetImageResults = e => {
       e.preventDefault();

        axios.get(`https://api.unsplash.com/search/photos?query=${image_input}&client_id=YwGCrVUctkLT42HxME8DOEZYdXrES_fX2rcEIM624Og`)
            .then(r => {
                if (r.data.total > 0){
                    setImageResults(r.data.results);
                }
            })
    }

    const handleEditImageTable = img => {
        const requestOptions = { name: table.name, image: img.urls.regular, token: token };

        editTable(table.id, requestOptions)
            .then(r =>{
                if (r.data.success){
                    setColumnChanged(!column_changed);
                }
            })
            .catch(e => props.history.push('/'));
    }
    return <TablePresentational
        table={table} columns={columns} dropIt={dropIt} allowDrop={allowDrop} dragStart={dragStart}
        createColumnSelected={createColumnSelected} setCreateColumnSelected={setCreateColumnSelected}
        handleCreateColumn={handleCreateColumn} handleDeleteColumn={handleDeleteColumn}
        setChangeToEdit={setChangeToEdit} changeToEdit={changeToEdit} handleEditColumn={handleEditColumn}
        handleCreateTask={handleCreateTask} setCreateTaskSelected={setCreateTaskSelected}
        createTaskSelected={createTaskSelected} select_edit_task_name={select_edit_task_name}
        setEditTaskName={setEditTaskName} select_edit_task_description={select_edit_task_description}
        setEditTaskDescription={setEditTaskDescription} handleEditTask={handleEditTask} setDescriptionTask={setDescriptionTask}
        handleDeleteTask={handleDeleteTask} handleGetImageResults={handleGetImageResults} setImageInput={setImageInput}
        image_input={image_input} image_results={image_results} handleEditImageTable={handleEditImageTable}/>
}