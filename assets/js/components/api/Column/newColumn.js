import axios from "axios";

export default function newColumn(params) {
    axios.post('/api/column/new', params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}