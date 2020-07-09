import axios from "axios";

export default function swapTasks(params) {
    return axios.put('/api/task/swap', params)
}