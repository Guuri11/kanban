import React, {useEffect, useState} from 'react';
import getTable from "../api/Table/getTable";
import TablePresentational from "../presentational/Table";

export default function Table(props) {

    const [table, setTable] = useState({});
    const {id} = props.match.params

    useEffect( () => {
        getTable(id).then( r => {
            if (r.data.success)
                setTable(r.data.results);
        } ).catch(e => props.history.push('/'))
    }, [] )

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
        }else
            targetEl.parentElement.insertBefore(targetEl, sourceIdEl)
    }

    const allowDrop = e => {
        e.preventDefault()
    }

    const dragStart = e => {
        e.dataTransfer.setData("text/plain", e.target.id);
    }

    return <TablePresentational table={table} dropIt={dropIt} allowDrop={allowDrop} dragStart={dragStart}/>
}