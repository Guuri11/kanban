import axios from "axios";

export default function getTables() {
    return axios.get('/api/table')
}