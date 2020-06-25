import axios from "axios";

export default function deleteTable(id) {
    axios.delete(`/api/table/delete/${id}`).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}