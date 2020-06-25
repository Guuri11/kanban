import axios from "axios";

export default function editColumn(id, params) {
    axios.put(`/api/column/edit/${id}`, params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}