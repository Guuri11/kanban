import axios from "axios";

export default function login() {
    axios.get('/api/user/logout').then(res => console.log(res))
        .catch(e=> console.log(e.response));
}