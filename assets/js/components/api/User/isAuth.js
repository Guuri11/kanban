import axios from "axios";

export default function isAuth() {
    return axios.get('/api/user/authenticated');
}