import axios from "axios";

export default function deleteColumn(id) {
    axios.delete(`/api/column/delete/${id}`).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}