import axios from "axios";

export default function deleteTask(id, requestOptions) {
    return axios.delete(`/api/task/delete/${id}`, requestOptions)
}