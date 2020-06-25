import axios from "axios";

export default function editTask(id, params) {
    axios.put(`/api/task/edit/${id}`, params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}