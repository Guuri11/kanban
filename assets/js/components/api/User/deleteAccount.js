import axios from "axios";

export default function deleteAccount() {
    axios.delete('/api/user/deleteaccount').then(res => console.log(res))
        .catch(e=> console.log(e.response));
}