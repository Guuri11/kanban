import axios from "axios";

export default function getTable(id) {
    return axios.get(`/api/table/${id}`)
}