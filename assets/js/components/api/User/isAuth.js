import axios from "axios";

export default function isAuth() {
    axios.get('/api/user/authenticated').then(res => {
        console.log(res);
    }).catch(e=>console.log(e.response))
}