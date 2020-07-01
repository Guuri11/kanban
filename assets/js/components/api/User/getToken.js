import axios from "axios";

export default function getToken() {
    return axios.get('/api/user/token');
}