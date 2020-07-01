import axios from "axios";

export default function editTable(id, params) {
    return axios.put(`/api/table/edit/${id}`, params)
}