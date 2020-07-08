import axios from "axios";

export default function newTask(params) {
    return axios.post('/api/task/new', params)
}