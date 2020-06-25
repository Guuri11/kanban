import axios from "axios";

export default function register(requestOptions) {
    axios.post('/api/user/register', requestOptions).then(res => console.log(res))
        .catch(e=> console.log(e.response));

}