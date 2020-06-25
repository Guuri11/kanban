import axios from "axios";

export default function getTable(id) {
    axios.get(`/api/table/${id}`).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}