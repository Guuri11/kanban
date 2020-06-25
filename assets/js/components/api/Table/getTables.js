import axios from "axios";

export default function getTables() {
    axios.get('/api/table').then(res => console.log(res))
        .catch(e=> console.log(e.response));

}