import axios from "axios";

export default function getToken() {
    axios.get('/api/user/token').then(res => console.log(res))
        .catch(e=> console.log(e.response));

}