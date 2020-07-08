import React from 'react';
import {Link, Redirect} from "react-router-dom";
import Header from "../container/Header";
import Footer from "../presentational/Footer";
import Loading from "./Sending";

export default function HomePresentational(props) {

    return (
        <div>
            <Header/>
            <div className={"container"}>
                <h2 className="mb-3 font-weight-bolder">{props.username} <button className="btn btn-sm btn-danger" onClick={props.handleDeleteAccount}>X</button></h2>
                {
                    props.errorDeleteAccount === '' ?
                        null
                        :
                        <p className="text-danger">{props.errorDeleteAccount}</p>
                }
                <h3><i className="fa fa-table text-info"/> Tablas</h3>
                <a href={'#collapse'} data-toggle="collapse"  role="button"
                   aria-expanded="false" aria-controls="collapse">
                    <i className="fa fa-plus text-success"/> Crear tabla
                </a>
                <div className="d-block">
                    <button className="btn btn-sm btn-info float-right ml-3" onClick={ () => props.setTableSection('show')}>
                        <i className="fa fa-eye text-white"/> Visualizar tablas
                    </button>
                    <button className="btn btn-sm btn-info float-right ml-3" onClick={ () => props.setTableSection('edit')}>
                        <i className="fa fa-edit text-white"/> Editar tablas
                    </button>
                </div>
                <div className="collapse m-2" id="collapse">
                    <div className="card card-body">
                        {
                            props.sending ?
                                <Loading/>
                                :
                                null
                        }
                        {
                            props.submited && props.success ?
                                <h5 className={"text-center text-success"}>¡Tabla creada!</h5>
                                :
                                <h5 className={"text-center text-danger"}>{props.error}</h5>
                        }
                        <form onSubmit={props.handleSubmitNewTable}>
                            <div className="form-group">
                                <label htmlFor="table">Nombre de la tabla</label>
                                <input type="text" className="form-control" id="table"/>
                            </div>
                            <button type="submit" className="btn btn-primary">Crear</button><br/>
                        </form>
                    </div>
                </div>
                {
                    props.errorDeleteTable === '' ?
                        null
                        :
                        <p className="text-danger">{props.errorDeleteTable}</p>
                }
                <div className="list-group">
                    {
                        props.tables.length > 0 ?
                            props.tables.map((table,id) => {
                                return (
                                        props.table_section === 'show' ?
                                            <div key={id} className="list-group-item list-group-item-action">
                                                <Link to={`/tabla/${table.id}`}>{table.name}</Link>
                                                <button className="btn btn-sm btn-danger float-right ml-1" onClick={() => props.handleDeleteTable(table.id, id)}>
                                                    <i className="fa fa-trash text-white"/>
                                                </button>
                                            </div>
                                            :
                                            props.table_section === 'edit' ?
                                                <div key={id} className="list-group-item list-group-item-action">
                                                    {
                                                        props.errorEditTable === '' ?
                                                            null
                                                            :
                                                            <p className="text-danger">{props.errorEditTable}</p>
                                                    }
                                                    <form onSubmit={e => props.handleEditTable(e,table.id, id)}>
                                                        <input type={'text'} id={'table-'+id} defaultValue={table.name}/>
                                                        <button className="btn btn-sm btn-success float-right ml-1">
                                                            <i className="fa fa-save text-white"/>
                                                        </button>
                                                    </form>
                                                </div>
                                                :
                                                <div key={id} className="list-group-item list-group-item-action">
                                                    <Link to={`/tabla/${table.id}`}>{table.name}</Link>
                                                    <button className="btn btn-sm btn-danger float-right ml-1" onClick={() => props.handleDeleteTable(table.id, id)}>
                                                        <i className="fa fa-trash text-white"/>
                                                    </button>
                                                </div>
                                )
                            })
                            :
                            <h4 className="text-dark">No has creado ninguna tabla aún</h4>
                    }
                </div>
            </div>
            <Footer/>
        </div>
    )
}