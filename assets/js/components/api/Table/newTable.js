import axios from "axios";

export default function newTable(params) {
    return axios.post('/api/table/new', params);
}