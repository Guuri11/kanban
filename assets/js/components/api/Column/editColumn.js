import axios from "axios";

export default function editColumn(id, params) {
    return axios.put(`/api/column/edit/${id}`, params)
}