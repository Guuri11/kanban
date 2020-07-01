import axios from "axios";

export default function getInfoUser() {
    return axios.get('/api/user/info');
}