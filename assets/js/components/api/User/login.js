import axios from "axios";

export default function login(requestOptions) {
    return axios.post('/api/user/login', requestOptions)
}