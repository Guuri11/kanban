import React, {useEffect, useState} from 'react';
import getTable from "../api/Table/getTable";
import TablePresentational from "../presentational/Table";
import getToken from "../api/User/getToken";
import newColumn from "../api/Column/newColumn";
import deleteColumn from "../api/Column/deleteColumn";
import editColumn from "../api/Column/editColumn";
import newTask from "../api/Task/newTask";

export default function Table(props) {

    const [table, setTable] = useState({});
    const [columns, setColumns] = useState([]);
    const [column_changed, setColumnChanged] = useState(false);
    const [tasks, setTasks] = useState([]);
    const [token, setToken] = useState('');
    const [createColumnSelected, setCreateColumnSelected] = useState(false);
    const [createTaskSelected, setCreateTaskSelected] = useState(false);
    const [change, setChange] = useState(false);
    const [changeToEdit, setChangeToEdit] = useState(false);
    const {id} = props.match.params

    useEffect( () => {
        getTable(id).then( r => {
            if (r.data.success){
                console.log(r.data.results)
                setTable(r.data.results);
                setColumns(r.data.results.columns);
                setTasks(r.data.results.columns.tasks);
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


        if (targetParentEl.id!==sourceIdParentEl.id){
            if (targetEl.className === sourceIdEl.className){
                targetParentEl.prepend(sourceIdEl);

            }else{
                targetEl.prepend(sourceIdEl);
            }

        }else {
            const clone1 = sourceIdEl.cloneNode(true)
            const clone2 = targetEl.cloneNode(true)

            targetEl.parentNode.replaceChild(clone1, targetEl);
            sourceIdEl.parentNode.replaceChild(clone2, sourceIdEl);

        }
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
            .catch(e=> console.log(e));
    }

    return <TablePresentational
        table={table} columns={columns} dropIt={dropIt} allowDrop={allowDrop} dragStart={dragStart}
        createColumnSelected={createColumnSelected} setCreateColumnSelected={setCreateColumnSelected}
        handleCreateColumn={handleCreateColumn} handleDeleteColumn={handleDeleteColumn}
        setChangeToEdit={setChangeToEdit} changeToEdit={changeToEdit} handleEditColumn={handleEditColumn}
        handleCreateTask={handleCreateTask} setCreateTaskSelected={setCreateTaskSelected}
        createTaskSelected={createTaskSelected}/>
}