import axios from "axios";

export default function newTable(params) {
    axios.post('/api/table/new', params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}