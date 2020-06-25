import axios from "axios";

export default function editTable(id, params) {
    axios.put(`/api/table/edit/${id}`, params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}