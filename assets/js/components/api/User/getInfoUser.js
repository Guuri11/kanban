import axios from "axios";

export default function getInfoUser() {
    axios.get('/api/user/info').then(res => console.log(res))
        .catch(e=> console.log(e.response));

}