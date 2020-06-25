import axios from "axios";

export default function login(requestOptions) {
    axios.post('/api/user/login', requestOptions).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}