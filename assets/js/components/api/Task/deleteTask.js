import axios from "axios";

export default function deleteTask(id) {
    axios.delete(`/api/task/delete/${id}`).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}