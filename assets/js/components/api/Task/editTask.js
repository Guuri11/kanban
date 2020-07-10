import axios from "axios";

export default function editTask(id, params) {
    return axios.put(`/api/task/edit/${id}`, params)
}