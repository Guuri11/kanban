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

    return <TablePresentational table={table}/>
}