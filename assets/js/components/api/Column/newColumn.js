import axios from "axios";

export default function newColumn(params) {
    return axios.post('/api/column/new', params)
}